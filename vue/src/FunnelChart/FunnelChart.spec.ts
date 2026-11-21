import { flushPromises, mount } from '@vue/test-utils';

// Jest only lets a mock use variables whose name starts with "mock".
const mockFetch = jest.fn();

jest.mock('CoreHome', () => ({
  AjaxHelper: { fetch: (...args: unknown[]) => mockFetch(...args) },
  NumberFormatter: {
    formatNumber: (value: number) => String(value),
    formatPercent: (value: number) => `${value}%`,
    formatCurrency: (value: number, currency: string) => `${currency}${value}`,
  },
  translate: (key: string, ...values: unknown[]) => `${key}(${values.join(',')})`,
}), { virtual: true });

// eslint-disable-next-line @typescript-eslint/no-var-requires
const FunnelChart = require('./FunnelChart.vue').default;

const steps = [
  {
    name: 'Cart', nb_visits: 4, funnel_proceeded: 1, funnel_exits: 3, funnel_avg_step_time: 0, idsubtable: 7,
  },
  {
    name: 'Order', nb_visits: 1, funnel_proceeded: null, funnel_exits: null, funnel_avg_step_time: 90, funnel_revenue: 30,
  },
];

function mountChart(props = {}) {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  return mount(FunnelChart as any, {
    props: { steps, currency: '$', ...props },
    global: {
      mocks: { translate: (key: string, ...values: unknown[]) => `${key}(${values.join(',')})` },
    },
  });
}

describe('RebelFunnels/FunnelChart', () => {
  beforeEach(() => mockFetch.mockReset());

  it('shows each step with its visits and its share of the first step', () => {
    const wrapper = mountChart();

    expect(wrapper.findAll('.funnelChart__visits').map((cell) => cell.text())).toEqual(['4', '1']);
    expect(wrapper.findAll('.funnelChart__share').map((cell) => cell.text())).toEqual(['100%', '25%']);
    expect(wrapper.find('.funnelChart__rate').text()).toBe('25%');
  });

  it('tells how many went on and left for every step but the last', () => {
    const wrapper = mountChart();

    const between = wrapper.findAll('.funnelChart__between');
    expect(between).toHaveLength(1);
    expect(between[0].text()).toContain('RebelFunnels_WentOn(1)');
    expect(between[0].text()).toContain('RebelFunnels_LeftHere(3,75%)');
    expect(between[0].text()).toContain('RebelFunnels_AvgTimeToNext(Intl_NMinutesShort(1) Intl_NSecondsShort(30))');
  });

  it('shows the revenue of a step that has some, in the currency of the website', () => {
    const wrapper = mountChart();

    expect(wrapper.findAll('.funnelChart__revenue').map((line) => line.text()))
      .toEqual(['RebelFunnels_RevenueOf($30)']);
  });

  it('draws a bar per step for each period or segment it is compared with', () => {
    const comparison = {
      label: 'Last month',
      steps: [{ ...steps[0], nb_visits: 10 }, { ...steps[1], nb_visits: 5 }],
    };
    const wrapper = mountChart({ comparisons: [comparison] });

    const rows = wrapper.findAll('.funnelChart__compare');
    expect(rows).toHaveLength(2);
    expect(rows.map((row) => row.find('.funnelChart__bar').attributes('style')))
      .toEqual(['width: 100%;', 'width: 50%;']);
    expect(rows[1].text()).toContain('Last month');
    expect(rows[1].find('.funnelChart__visits').text()).toBe('5');
    expect(wrapper.find('.funnelChart__comparison').text()).toContain('50%');
  });

  it('says that there is no data when no visit entered', () => {
    const wrapper = mountChart({ steps: steps.map((step) => ({ ...step, nb_visits: 0 })) });

    expect(wrapper.find('.funnelChart__empty').exists()).toBe(true);
    expect(wrapper.find('.funnelChart__steps').exists()).toBe(false);
  });

  it('loads what the visits that left did next when their number is clicked', async () => {
    mockFetch.mockResolvedValue([{ label: 'Left the website', nb_visits: 2 }]);
    const wrapper = mountChart({ idFunnel: 5 });

    await wrapper.find('.funnelChart__exits--link').trigger('click');
    await flushPromises();

    expect(mockFetch).toHaveBeenCalledWith(expect.objectContaining({
      method: 'RebelFunnels.getSteps', idFunnel: 5, idSubtable: 7,
    }));
    expect(wrapper.find('.funnelChart__nextLabel').text()).toBe('Left the website');
  });

  it('offers no list of what visits did next without a saved funnel', () => {
    const wrapper = mountChart();

    expect(wrapper.find('.funnelChart__exits--link').exists()).toBe(false);
  });
});
