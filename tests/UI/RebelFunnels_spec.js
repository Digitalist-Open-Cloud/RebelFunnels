describe('RebelFunnels', function () {
    this.fixture = 'Piwik\\Plugins\\RebelFunnels\\tests\\Fixtures\\SiteWithFunnelVisits';

    const params = 'idSite=1&period=day&date=2024-03-05';
    const pageUrl = (subcategory) => `?module=CoreHome&action=index&${params}#?${params}`
        + `&category=RebelFunnels_Funnels&subcategory=${subcategory}`;

    before(function () {
        testEnvironment.pluginsToLoad = ['RebelFunnels'];
        testEnvironment.save();
    });

    async function openManagePage() {
        await page.goto(pageUrl('RebelFunnels_ManageFunnels'));
        await page.waitForSelector('.funnelsList table', { visible: true });
        await page.waitForNetworkIdle();
    }

    function funnelNames() {
        return page.evaluate(() => [...document.querySelectorAll('.funnelsList__name')]
            .map((name) => name.textContent.trim()));
    }

    async function clickInRow(funnelName, selector) {
        const button = await page.jQuery(`.funnelsList tr:contains(${funnelName}) ${selector}`);
        await button.click();
    }

    async function save() {
        await page.click('.funnelEditor__save input');
        await page.waitForSelector('.funnelsList table', { visible: true });
        await page.waitForNetworkIdle();
    }

    it('should tell that there are no funnels yet', async function () {
        await openManagePage();

        const text = await page.evaluate(() => document.querySelector('.funnelsList tbody').textContent);
        expect(text).to.contain('There are no funnels yet.');
    });

    it('should create a funnel in the editor', async function () {
        await page.click('.funnelsList .tableActionBar .btn');
        await page.waitForSelector('.funnelEditor');
        await page.type('.funnelEditor input[name=name]', 'Checkout');
        await page.type('.funnelEditor input[name=stepName0]', 'Cart');
        await page.type('.funnelEditor input[name=ruleValue0_0]', '/cart');
        await page.type('.funnelEditor input[name=stepName1]', 'Shipping');
        await page.type('.funnelEditor input[name=ruleValue1_0]', '/shipping');
        await save();

        expect(await funnelNames()).to.deep.equal(['Checkout']);
    });

    it('should tell which steps a pasted URL matches', async function () {
        await clickInRow('Checkout', '.icon-edit');
        await page.waitForSelector('.funnelEditor');
        await page.type('.funnelEditor input[name=testUrl]', 'https://www.example.org/shipping?from=cart-page');
        await page.waitForSelector('.funnelEditor__matches');

        const matching = await page.evaluate(() => [...document.querySelectorAll('.funnelEditor__step')]
            .map((step) => !!step.querySelector('.funnelEditor__matches')));
        expect(matching).to.deep.equal([false, true]);
    });

    it('should keep a step added when editing', async function () {
        await page.click('.funnelEditor__addStep');
        await page.type('.funnelEditor input[name=stepName2]', 'Payment');
        await page.type('.funnelEditor input[name=ruleValue2_0]', '/payment');
        await save();

        await clickInRow('Checkout', '.icon-edit');
        await page.waitForSelector('.funnelEditor');
        const steps = await page.evaluate(() => [...document.querySelectorAll('.funnelEditor__stepName')]
            .map((input) => input.value));
        await page.click('.funnelEditor__cancel');

        expect(steps).to.deep.equal(['Cart', 'Shipping', 'Payment']);
    });

    it('should show the steps of the funnel on its page', async function () {
        await page.goto(pageUrl('RebelFunnels_funnel_1'));
        await page.waitForSelector('.funnelChart__step');
        await page.waitForNetworkIdle();

        const visits = await page.evaluate(() => [...document.querySelectorAll('.funnelChart__visits')]
            .map((count) => count.textContent.trim()));
        const rate = await page.evaluate(() => document.querySelector('.funnelChart__rate').textContent.trim());
        expect(visits).to.deep.equal(['4', '2', '1']);
        expect(rate).to.equal('25%');
    });

    it('should show what the visits that left at a step did next', async function () {
        await page.click('.funnelChart__exits--link');
        await page.waitForSelector('.funnelChart__next li');

        const actions = await page.evaluate(() => [...document.querySelectorAll('.funnelChart__nextLabel')]
            .map((label) => label.textContent.trim()));
        expect(actions).to.have.members(['Left the website', 'Event: pay']);
    });

    it('should keep a goal of the website as a step', async function () {
        await openManagePage();
        await page.click('.funnelsList .tableActionBar .btn');
        await page.waitForSelector('.funnelEditor');
        await page.type('.funnelEditor input[name=name]', 'To the goal');
        await page.type('.funnelEditor input[name=stepName0]', 'Cart');
        await page.type('.funnelEditor input[name=ruleValue0_0]', '/cart');
        await page.type('.funnelEditor input[name=stepName1]', 'Converted');
        await page.select('.funnelEditor select[name=ruleType1_0]', 'goal');
        await (await page.jQuery('select[name=ruleGoal1_0] option:contains(Thanks page)', { waitFor: true })).evaluate((option) => {
            option.selected = true;
            option.parentElement.dispatchEvent(new Event('change'));
        });
        await save();

        await clickInRow('To the goal', '.icon-edit');
        await page.waitForSelector('.funnelEditor');
        // The goals are loaded after the editor opens.
        await page.jQuery('select[name=ruleGoal1_0] option:contains(Thanks page)', { waitFor: true });
        const goal = await page.evaluate(() => {
            const select = document.querySelector('select[name=ruleGoal1_0]');
            return select.options[select.selectedIndex].textContent.trim();
        });
        await page.click('.funnelEditor__cancel');
        expect(goal).to.equal('Thanks page');
    });

    it('should open the editor filled in from an example', async function () {
        await openManagePage();
        await (await page.jQuery('.funnelsList__example:contains(How deep visits go)')).click();
        await page.waitForSelector('.funnelEditor');

        const steps = await page.evaluate(() => [...document.querySelectorAll('.funnelEditor__stepName')]
            .map((input) => input.value));
        await page.click('.funnelEditor__cancel');
        expect(steps).to.deep.equal(['A page', 'A second page', 'A third page']);
    });

    it('should show the documentation with the limit as it is set', async function () {
        await page.goto(`?module=RebelFunnels&action=documentation&${params}`);
        await page.waitForSelector('.rebelFunnelsDocumentation');

        const text = await page.evaluate(() => document.querySelector('.rebelFunnelsDocumentation').textContent);
        expect(text).to.contain('A funnel can have at most 10 steps.');
    });

    it('should delete a funnel after confirming', async function () {
        await openManagePage();
        await clickInRow('Checkout', '.icon-delete');
        await (await page.jQuery('.modal.open .modal-footer a:contains(Yes)', { waitFor: true })).click();
        await page.waitForNetworkIdle();

        expect(await funnelNames()).to.deep.equal(['To the goal']);
    });
});
