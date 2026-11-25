(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory(require("CoreHome"), require("vue"), require("CorePluginsAdmin"), require("SegmentEditor"));
	else if(typeof define === 'function' && define.amd)
		define(["CoreHome", , "CorePluginsAdmin", "SegmentEditor"], factory);
	else if(typeof exports === 'object')
		exports["RebelFunnels"] = factory(require("CoreHome"), require("vue"), require("CorePluginsAdmin"), require("SegmentEditor"));
	else
		root["RebelFunnels"] = factory(root["CoreHome"], root["Vue"], root["CorePluginsAdmin"], root["SegmentEditor"]);
})((typeof self !== 'undefined' ? self : this), function(__WEBPACK_EXTERNAL_MODULE__19dc__, __WEBPACK_EXTERNAL_MODULE__8bbf__, __WEBPACK_EXTERNAL_MODULE_a5a2__, __WEBPACK_EXTERNAL_MODULE_f06f__) {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "plugins/RebelFunnels/vue/dist/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "fae3");
/******/ })
/************************************************************************/
/******/ ({

/***/ "19dc":
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE__19dc__;

/***/ }),

/***/ "8bbf":
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE__8bbf__;

/***/ }),

/***/ "a5a2":
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE_a5a2__;

/***/ }),

/***/ "f06f":
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE_f06f__;

/***/ }),

/***/ "fae3":
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, "FunnelChart", function() { return /* reexport */ FunnelChart; });
__webpack_require__.d(__webpack_exports__, "FunnelDuplicateForm", function() { return /* reexport */ FunnelDuplicateForm; });
__webpack_require__.d(__webpack_exports__, "FunnelEditor", function() { return /* reexport */ FunnelEditor; });
__webpack_require__.d(__webpack_exports__, "FunnelsList", function() { return /* reexport */ FunnelsList; });
__webpack_require__.d(__webpack_exports__, "ManageFunnels", function() { return /* reexport */ ManageFunnels; });

// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/setPublicPath.js
// This file is imported into lib/wc client bundles.

if (typeof window !== 'undefined') {
  var currentScript = window.document.currentScript
  if (false) { var getCurrentScript; }

  var src = currentScript && currentScript.src.match(/(.+\/)[^/]+\.js(\?.*)?$/)
  if (src) {
    __webpack_require__.p = src[1] // eslint-disable-line
  }
}

// Indicate to webpack that this file can be concatenated
/* harmony default export */ var setPublicPath = (null);

// EXTERNAL MODULE: external {"commonjs":"vue","commonjs2":"vue","root":"Vue"}
var external_commonjs_vue_commonjs2_vue_root_Vue_ = __webpack_require__("8bbf");

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/RebelFunnels/vue/src/FunnelChart/FunnelChart.vue?vue&type=template&id=57af51d6

const _hoisted_1 = {
  class: "funnelChart"
};
const _hoisted_2 = {
  key: 0,
  class: "funnelChart__empty"
};
const _hoisted_3 = {
  class: "funnelChart__summary"
};
const _hoisted_4 = {
  class: "funnelChart__rate"
};
const _hoisted_5 = {
  class: "funnelChart__rateLabel"
};
const _hoisted_6 = {
  class: "funnelChart__counts"
};
const _hoisted_7 = {
  key: 0,
  class: "funnelChart__counts"
};
const _hoisted_8 = {
  class: "funnelChart__steps"
};
const _hoisted_9 = {
  class: "funnelChart__head"
};
const _hoisted_10 = {
  class: "funnelChart__number"
};
const _hoisted_11 = {
  class: "funnelChart__name"
};
const _hoisted_12 = {
  class: "funnelChart__visits"
};
const _hoisted_13 = {
  class: "funnelChart__share"
};
const _hoisted_14 = {
  class: "funnelChart__track"
};
const _hoisted_15 = {
  class: "funnelChart__compareHead"
};
const _hoisted_16 = {
  class: "funnelChart__compareLabel"
};
const _hoisted_17 = {
  class: "funnelChart__visits"
};
const _hoisted_18 = {
  class: "funnelChart__share"
};
const _hoisted_19 = {
  class: "funnelChart__track funnelChart__track--compare"
};
const _hoisted_20 = {
  key: 0,
  class: "funnelChart__revenue"
};
const _hoisted_21 = {
  key: 1,
  class: "funnelChart__between"
};
const _hoisted_22 = {
  class: "funnelChart__proceeded"
};
const _hoisted_23 = ["title", "aria-expanded", "onClick"];
const _hoisted_24 = {
  key: 1,
  class: "funnelChart__exits"
};
const _hoisted_25 = {
  key: 2,
  class: "funnelChart__time"
};
const _hoisted_26 = {
  key: 2,
  class: "funnelChart__next"
};
const _hoisted_27 = {
  class: "funnelChart__nextTitle"
};
const _hoisted_28 = {
  key: 0,
  class: "loadingPiwik"
};
const _hoisted_29 = {
  key: 1
};
const _hoisted_30 = {
  class: "funnelChart__nextLabel"
};
function render(_ctx, _cache, $props, $setup, $data, $options) {
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_1, [!_ctx.entries ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", _hoisted_2, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('CoreHome_ThereIsNoDataForThisReport')), 1)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
    key: 1
  }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_3, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_4, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.percent(_ctx.conversions, _ctx.entries)), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_5, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('General_ColumnConversionRate')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_6, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_ConversionsOfEntries', _ctx.number(_ctx.conversions), _ctx.number(_ctx.entries))), 1), _ctx.revenueOfLastStep ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", _hoisted_7, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_RevenueOf', _ctx.money(_ctx.revenueOfLastStep))), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.comparisons, (comparison, index) => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", {
      class: "funnelChart__comparison",
      key: index
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
      class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(["funnelChart__dot", `funnelChart__series${index % 4}`])
    }, null, 2), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(comparison.label) + ": ", 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("strong", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.percent(_ctx.lastVisits(comparison), _ctx.firstVisits(comparison))), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_ConversionsOfEntries', _ctx.number(_ctx.lastVisits(comparison)), _ctx.number(_ctx.firstVisits(comparison)))), 1)]);
  }), 128)), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("ol", _hoisted_8, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.steps, (step, index) => {
    return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("li", {
      class: "funnelChart__step",
      key: index
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_9, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_10, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(index + 1), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_11, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(step.name), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_12, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.number(step.nb_visits)), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_13, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.percent(step.nb_visits, _ctx.entries)), 1)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_14, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", {
      class: "funnelChart__bar",
      style: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeStyle"])({
        width: `${step.nb_visits / _ctx.entries * 100}%`
      })
    }, null, 4)]), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.comparisons, (comparison, comparisonIndex) => {
      return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", {
        class: "funnelChart__compare",
        key: comparisonIndex
      }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_15, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_16, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(comparison.label), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_17, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.number(_ctx.visitsOf(comparison, index))), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_18, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.percent(_ctx.visitsOf(comparison, index), _ctx.firstVisits(comparison))), 1)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_19, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", {
        class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(["funnelChart__bar", `funnelChart__series${comparisonIndex % 4}`]),
        style: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeStyle"])({
          width: `${_ctx.shareOf(comparison, index)}%`
        })
      }, null, 6)])]);
    }), 128)), step.funnel_revenue ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_20, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_RevenueOf', _ctx.money(step.funnel_revenue))), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), step.funnel_proceeded !== null ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_21, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_22, " ↓ " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_WentOn', _ctx.number(step.funnel_proceeded))), 1), _ctx.idFunnel && step.idsubtable && step.funnel_exits ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("button", {
      key: 0,
      type: "button",
      class: "funnelChart__exits funnelChart__exits--link",
      title: _ctx.translate('RebelFunnels_ShowNext'),
      "aria-expanded": _ctx.opened === index,
      onClick: $event => _ctx.toggle(index, step.idsubtable)
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.leftText(step)), 9, _hoisted_23)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", _hoisted_24, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.leftText(step)), 1)), step.funnel_proceeded ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", _hoisted_25, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_AvgTimeToNext', _ctx.duration(_ctx.steps[index + 1].funnel_avg_step_time))), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.opened === index ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_26, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_27, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_WhereTheyWent')), 1), !_ctx.nextActions ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", _hoisted_28, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('General_LoadingData')), 1)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("ul", _hoisted_29, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.nextActions, (action, actionIndex) => {
      return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("li", {
        key: actionIndex
      }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_30, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(action.label), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.number(action.nb_visits)), 1)]);
    }), 128))]))])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]);
  }), 128))])], 64))]);
}
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/FunnelChart/FunnelChart.vue?vue&type=template&id=57af51d6

// EXTERNAL MODULE: external "CoreHome"
var external_CoreHome_ = __webpack_require__("19dc");

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/RebelFunnels/vue/src/FunnelChart/FunnelChart.vue?vue&type=script&lang=ts


/* harmony default export */ var FunnelChartvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  props: {
    steps: {
      type: Array,
      required: true
    },
    comparisons: {
      type: Array,
      default: () => []
    },
    // The currency symbol of the website, for the revenue of goal steps.
    currency: {
      type: String,
      default: ''
    },
    // Needed to load what visits did after a step.
    idFunnel: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      opened: null,
      nextActions: null
    };
  },
  computed: {
    entries() {
      return this.steps.length ? this.steps[0].nb_visits : 0;
    },
    conversions() {
      return this.steps.length ? this.steps[this.steps.length - 1].nb_visits : 0;
    },
    revenueOfLastStep() {
      return this.steps.length ? this.steps[this.steps.length - 1].funnel_revenue || 0 : 0;
    }
  },
  methods: {
    leftText(step) {
      return Object(external_CoreHome_["translate"])('RebelFunnels_LeftHere', this.number(step.funnel_exits), this.percent(step.funnel_exits, step.nb_visits));
    },
    toggle(index, idSubtable) {
      if (this.opened === index) {
        this.opened = null;
        return;
      }
      this.opened = index;
      this.nextActions = null;
      external_CoreHome_["AjaxHelper"].fetch({
        method: 'RebelFunnels.getSteps',
        idFunnel: this.idFunnel,
        idSubtable,
        filter_limit: '-1'
      }).then(actions => {
        if (this.opened === index) {
          this.nextActions = actions;
        }
      });
    },
    firstVisits(comparison) {
      return comparison.steps.length ? comparison.steps[0].nb_visits : 0;
    },
    lastVisits(comparison) {
      return this.visitsOf(comparison, comparison.steps.length - 1);
    },
    visitsOf(comparison, index) {
      return comparison.steps[index] ? comparison.steps[index].nb_visits : 0;
    },
    // The width of the bar of a step: its share of the visits that entered that funnel.
    shareOf(comparison, index) {
      const entries = this.firstVisits(comparison);
      return entries ? this.visitsOf(comparison, index) / entries * 100 : 0;
    },
    money(value) {
      return external_CoreHome_["NumberFormatter"].formatCurrency(value, this.currency, 2, 0);
    },
    number(value) {
      return external_CoreHome_["NumberFormatter"].formatNumber(value || 0);
    },
    percent(part, whole) {
      return external_CoreHome_["NumberFormatter"].formatPercent(whole ? (part || 0) / whole * 100 : 0, 1);
    },
    duration(seconds) {
      const parts = [['Intl_NHoursShort', Math.floor(seconds / 3600)], ['Intl_NMinutesShort', Math.floor(seconds % 3600 / 60)], ['Intl_NSecondsShort', seconds % 60]].filter(([, value]) => value);
      return parts.length ? parts.slice(0, 2).map(([key, value]) => Object(external_CoreHome_["translate"])(key, value)).join(' ') : Object(external_CoreHome_["translate"])('Intl_NSecondsShort', 0);
    }
  }
}));
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/FunnelChart/FunnelChart.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/FunnelChart/FunnelChart.vue



FunnelChartvue_type_script_lang_ts.render = render

/* harmony default export */ var FunnelChart = (FunnelChartvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/RebelFunnels/vue/src/FunnelDuplicateForm/FunnelDuplicateForm.vue?vue&type=template&id=10289878

const FunnelDuplicateFormvue_type_template_id_10289878_hoisted_1 = {
  class: "funnelDuplicateForm"
};
const FunnelDuplicateFormvue_type_template_id_10289878_hoisted_2 = {
  class: "funnelDuplicateForm__text"
};
const FunnelDuplicateFormvue_type_template_id_10289878_hoisted_3 = {
  class: "funnelDuplicateForm__site"
};
const FunnelDuplicateFormvue_type_template_id_10289878_hoisted_4 = ["disabled"];
function FunnelDuplicateFormvue_type_template_id_10289878_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_SiteSelector = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("SiteSelector");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", FunnelDuplicateFormvue_type_template_id_10289878_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", FunnelDuplicateFormvue_type_template_id_10289878_hoisted_2, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_DuplicateTo', _ctx.itemName)), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", FunnelDuplicateFormvue_type_template_id_10289878_hoisted_3, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_SiteSelector, {
    modelValue: _ctx.site,
    "onUpdate:modelValue": _cache[0] || (_cache[0] = $event => _ctx.site = $event),
    "show-selected-site": true,
    "show-all-sites-item": false,
    "switch-site-on-select": false,
    "only-sites-with-at-least-write-access": true
  }, null, 8, ["modelValue"])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "button",
    class: "btn funnelDuplicateForm__confirm",
    disabled: _ctx.isBusy,
    onClick: _cache[1] || (_cache[1] = $event => _ctx.$emit('confirm', _ctx.site))
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_Duplicate')), 9, FunnelDuplicateFormvue_type_template_id_10289878_hoisted_4), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
    type: "button",
    class: "funnelDuplicateForm__cancel",
    onClick: _cache[2] || (_cache[2] = $event => _ctx.$emit('cancel'))
  }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('General_Cancel')), 1)]);
}
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/FunnelDuplicateForm/FunnelDuplicateForm.vue?vue&type=template&id=10289878

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/RebelFunnels/vue/src/FunnelDuplicateForm/FunnelDuplicateForm.vue?vue&type=script&lang=ts


/* harmony default export */ var FunnelDuplicateFormvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  props: {
    itemName: {
      type: String,
      required: true
    },
    isBusy: Boolean
  },
  emits: ['confirm', 'cancel'],
  components: {
    SiteSelector: external_CoreHome_["SiteSelector"]
  },
  data() {
    return {
      site: {
        id: external_CoreHome_["Matomo"].idSite,
        name: external_CoreHome_["Matomo"].helper.htmlDecode(external_CoreHome_["Matomo"].siteName)
      }
    };
  }
}));
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/FunnelDuplicateForm/FunnelDuplicateForm.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/FunnelDuplicateForm/FunnelDuplicateForm.vue



FunnelDuplicateFormvue_type_script_lang_ts.render = FunnelDuplicateFormvue_type_template_id_10289878_render

/* harmony default export */ var FunnelDuplicateForm = (FunnelDuplicateFormvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/RebelFunnels/vue/src/FunnelEditor/FunnelEditor.vue?vue&type=template&id=56111fec

const FunnelEditorvue_type_template_id_56111fec_hoisted_1 = {
  class: "funnelEditor"
};
const FunnelEditorvue_type_template_id_56111fec_hoisted_2 = {
  class: "funnelEditor__form"
};
const FunnelEditorvue_type_template_id_56111fec_hoisted_3 = {
  class: "funnelEditor__sectionTitle"
};
const FunnelEditorvue_type_template_id_56111fec_hoisted_4 = {
  class: "funnelEditor__help"
};
const FunnelEditorvue_type_template_id_56111fec_hoisted_5 = ["draggable", "onDragstart", "onDrop"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_6 = {
  class: "funnelEditor__stepHead"
};
const FunnelEditorvue_type_template_id_56111fec_hoisted_7 = ["title", "aria-label", "onMousedown", "onKeydown"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_8 = {
  class: "funnelEditor__stepNumber"
};
const FunnelEditorvue_type_template_id_56111fec_hoisted_9 = ["name", "onUpdate:modelValue", "placeholder", "aria-label"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_10 = {
  key: 0,
  class: "funnelEditor__matches"
};
const FunnelEditorvue_type_template_id_56111fec_hoisted_11 = ["title", "aria-label", "disabled", "onClick"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_12 = {
  class: "funnelEditor__or"
};
const FunnelEditorvue_type_template_id_56111fec_hoisted_13 = ["name", "onUpdate:modelValue", "aria-label", "onChange"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_14 = ["value"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_15 = ["name", "onUpdate:modelValue", "aria-label"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_16 = {
  value: "",
  disabled: ""
};
const FunnelEditorvue_type_template_id_56111fec_hoisted_17 = ["value"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_18 = ["name", "onUpdate:modelValue", "aria-label"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_19 = ["value"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_20 = ["name", "onUpdate:modelValue", "list", "placeholder", "aria-label", "onFocus"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_21 = ["title", "aria-label", "onClick"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_22 = ["onClick"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_23 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
  class: "icon-add"
}, null, -1);
const FunnelEditorvue_type_template_id_56111fec_hoisted_24 = {
  key: 0,
  class: "funnelEditor__direct"
};
const FunnelEditorvue_type_template_id_56111fec_hoisted_25 = ["name", "onUpdate:modelValue"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_26 = ["id"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_27 = ["value"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_28 = ["disabled"];
const FunnelEditorvue_type_template_id_56111fec_hoisted_29 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
  class: "icon-add"
}, null, -1);
const FunnelEditorvue_type_template_id_56111fec_hoisted_30 = {
  key: 0,
  class: "funnelEditor__help"
};
const _hoisted_31 = {
  class: "funnelEditor__sectionTitle"
};
const _hoisted_32 = {
  class: "funnelEditor__help"
};
const _hoisted_33 = ["aria-label"];
const _hoisted_34 = {
  key: 1,
  class: "funnelEditor__help"
};
const _hoisted_35 = {
  class: "funnelEditor__sectionTitle"
};
const _hoisted_36 = {
  class: "funnelEditor__help"
};
const _hoisted_37 = {
  class: "funnelEditor__segment"
};
const _hoisted_38 = {
  key: 2,
  class: "funnelEditor__field"
};
const _hoisted_39 = {
  key: 3,
  class: "funnelEditor__warning"
};
const _hoisted_40 = {
  class: "funnelEditor__actions"
};
const _hoisted_41 = {
  class: "funnelEditor__save"
};
const _hoisted_42 = {
  class: "funnelEditor__preview"
};
const _hoisted_43 = {
  class: "funnelEditor__previewTitle"
};
const _hoisted_44 = {
  class: "funnelEditor__help"
};
const _hoisted_45 = {
  key: 0,
  class: "funnelEditor__help"
};
const _hoisted_46 = {
  key: 1,
  class: "funnelEditor__error"
};
const _hoisted_47 = {
  key: 2
};
const _hoisted_48 = {
  class: "loadingPiwik"
};
function FunnelEditorvue_type_template_id_56111fec_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_Field = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("Field");
  const _component_SegmentGenerator = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("SegmentGenerator");
  const _component_Notification = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("Notification");
  const _component_SaveButton = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("SaveButton");
  const _component_MatomoLoader = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("MatomoLoader");
  const _component_FunnelChart = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("FunnelChart");
  const _component_ContentBlock = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("ContentBlock");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", FunnelEditorvue_type_template_id_56111fec_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_ContentBlock, {
    "content-title": _ctx.title
  }, {
    default: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withCtx"])(() => [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("form", {
      class: "funnelEditor__layout",
      onSubmit: _cache[11] || (_cache[11] = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])($event => _ctx.save(), ["prevent"]))
    }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", FunnelEditorvue_type_template_id_56111fec_hoisted_2, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_Field, {
      uicontrol: "text",
      name: "name",
      modelValue: _ctx.form.name,
      "onUpdate:modelValue": _cache[0] || (_cache[0] = $event => _ctx.form.name = $event),
      maxlength: 100,
      "full-width": true,
      title: _ctx.translate('General_Name')
    }, null, 8, ["modelValue", "title"]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_Field, {
      uicontrol: "textarea",
      name: "description",
      modelValue: _ctx.form.description,
      "onUpdate:modelValue": _cache[1] || (_cache[1] = $event => _ctx.form.description = $event),
      maxlength: 1000,
      "full-width": true,
      title: _ctx.translate('General_Description'),
      "ui-control-attributes": {
        class: 'compact-textarea'
      }
    }, null, 8, ["modelValue", "title"]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h3", FunnelEditorvue_type_template_id_56111fec_hoisted_3, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_Steps')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", FunnelEditorvue_type_template_id_56111fec_hoisted_4, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_StepsHelp')), 1), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.form.steps, (step, index) => {
      return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", {
        class: "funnelEditor__step",
        key: step.key,
        draggable: _ctx.grabbed === index,
        onDragstart: $event => _ctx.dragged = index,
        onDragend: _cache[3] || (_cache[3] = $event => _ctx.grabbed = null),
        onDragover: _cache[4] || (_cache[4] = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])(() => {}, ["prevent"])),
        onDrop: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])($event => _ctx.moveStep(_ctx.dragged, index), ["prevent"])
      }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", FunnelEditorvue_type_template_id_56111fec_hoisted_6, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
        type: "button",
        class: "funnelEditor__handle btn-flat icon-menu-hamburger",
        title: _ctx.translate('RebelFunnels_Reorder'),
        "aria-label": _ctx.translate('RebelFunnels_Reorder'),
        onMousedown: $event => _ctx.grabbed = index,
        onMouseup: _cache[2] || (_cache[2] = $event => _ctx.grabbed = null),
        onKeydown: [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withKeys"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])($event => _ctx.moveStep(index, index - 1), ["prevent"]), ["up"]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withKeys"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])($event => _ctx.moveStep(index, index + 1), ["prevent"]), ["down"])]
      }, null, 40, FunnelEditorvue_type_template_id_56111fec_hoisted_7), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", FunnelEditorvue_type_template_id_56111fec_hoisted_8, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(index + 1), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
        type: "text",
        class: "funnelEditor__stepName browser-default",
        name: `stepName${index}`,
        "onUpdate:modelValue": $event => step.name = $event,
        maxlength: "100",
        placeholder: _ctx.translate('RebelFunnels_StepName'),
        "aria-label": _ctx.translate('RebelFunnels_StepName')
      }, null, 8, FunnelEditorvue_type_template_id_56111fec_hoisted_9), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelText"], step.name]]), _ctx.testResult[index] ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", FunnelEditorvue_type_template_id_56111fec_hoisted_10, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_MatchesTestUrl')), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
        type: "button",
        class: "funnelEditor__removeStep btn-flat icon-delete",
        title: _ctx.translate('RebelFunnels_RemoveStep'),
        "aria-label": _ctx.translate('RebelFunnels_RemoveStep'),
        disabled: _ctx.form.steps.length <= 2,
        onClick: $event => _ctx.form.steps.splice(index, 1)
      }, null, 8, FunnelEditorvue_type_template_id_56111fec_hoisted_11)]), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(step.rules, (rule, ruleIndex) => {
        return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", {
          class: "funnelEditor__rule",
          key: ruleIndex
        }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", FunnelEditorvue_type_template_id_56111fec_hoisted_12, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(ruleIndex ? _ctx.translate('General_Or') : ''), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("select", {
          class: "browser-default",
          name: `ruleType${index}_${ruleIndex}`,
          "onUpdate:modelValue": $event => rule.type = $event,
          "aria-label": _ctx.translate('RebelFunnels_RuleType'),
          onChange: $event => _ctx.onTypeChange(rule)
        }, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.ruleTypes, type => {
          return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("option", {
            key: type,
            value: type
          }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate(`RebelFunnels_RuleType_${type}`)), 9, FunnelEditorvue_type_template_id_56111fec_hoisted_14);
        }), 128))], 40, FunnelEditorvue_type_template_id_56111fec_hoisted_13), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelSelect"], rule.type]]), rule.type === 'goal' ? Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])((Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("select", {
          key: 0,
          class: "funnelEditor__ruleValue browser-default",
          name: `ruleGoal${index}_${ruleIndex}`,
          "onUpdate:modelValue": $event => rule.value = $event,
          "aria-label": _ctx.translate('RebelFunnels_ChooseGoal')
        }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("option", FunnelEditorvue_type_template_id_56111fec_hoisted_16, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_ChooseGoal')), 1), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.goals, goal => {
          return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("option", {
            key: goal.value,
            value: goal.value
          }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(goal.name), 9, FunnelEditorvue_type_template_id_56111fec_hoisted_17);
        }), 128))], 8, FunnelEditorvue_type_template_id_56111fec_hoisted_15)), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelSelect"], rule.value]]) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
          key: 1
        }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("select", {
          class: "browser-default",
          name: `ruleOperator${index}_${ruleIndex}`,
          "onUpdate:modelValue": $event => rule.operator = $event,
          "aria-label": _ctx.translate('RebelFunnels_RuleOperator')
        }, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.ruleOperators, operator => {
          return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("option", {
            key: operator,
            value: operator
          }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate(`RebelFunnels_RuleOperator_${operator}`)), 9, FunnelEditorvue_type_template_id_56111fec_hoisted_19);
        }), 128))], 8, FunnelEditorvue_type_template_id_56111fec_hoisted_18), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelSelect"], rule.operator]]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
          type: "text",
          class: "funnelEditor__ruleValue browser-default",
          name: `ruleValue${index}_${ruleIndex}`,
          "onUpdate:modelValue": $event => rule.value = $event,
          maxlength: "1000",
          list: `funnelEditorValues_${rule.type}`,
          placeholder: _ctx.translate(`RebelFunnels_RuleExample_${rule.type}`),
          "aria-label": _ctx.translate('General_Value'),
          onFocus: $event => _ctx.fetchSuggestions(rule.type)
        }, null, 40, FunnelEditorvue_type_template_id_56111fec_hoisted_20), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelText"], rule.value]])], 64)), step.rules.length > 1 ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("button", {
          key: 2,
          type: "button",
          class: "funnelEditor__removeRule btn-flat icon-close",
          title: _ctx.translate('General_Remove'),
          "aria-label": _ctx.translate('General_Remove'),
          onClick: $event => step.rules.splice(ruleIndex, 1)
        }, null, 8, FunnelEditorvue_type_template_id_56111fec_hoisted_21)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]);
      }), 128)), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
        type: "button",
        class: "funnelEditor__addRule btn-flat",
        onClick: $event => _ctx.addRule(step)
      }, [FunnelEditorvue_type_template_id_56111fec_hoisted_23, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_AddRule')), 1)], 8, FunnelEditorvue_type_template_id_56111fec_hoisted_22), index > 0 ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("label", FunnelEditorvue_type_template_id_56111fec_hoisted_24, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
        type: "checkbox",
        name: `stepDirect${index}`,
        "onUpdate:modelValue": $event => step.direct = $event
      }, null, 8, FunnelEditorvue_type_template_id_56111fec_hoisted_25), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelCheckbox"], step.direct]]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_Direct')), 1)])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 40, FunnelEditorvue_type_template_id_56111fec_hoisted_5);
    }), 128)), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.suggestions, (values, type) => {
      return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("datalist", {
        key: type,
        id: `funnelEditorValues_${type}`
      }, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(values, value => {
        return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("option", {
          key: value,
          value: value
        }, null, 8, FunnelEditorvue_type_template_id_56111fec_hoisted_27);
      }), 128))], 8, FunnelEditorvue_type_template_id_56111fec_hoisted_26);
    }), 128)), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
      type: "button",
      class: "funnelEditor__addStep btn-flat",
      disabled: _ctx.form.steps.length >= _ctx.maxSteps,
      onClick: _cache[5] || (_cache[5] = $event => _ctx.addStep())
    }, [FunnelEditorvue_type_template_id_56111fec_hoisted_29, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_AddStep')), 1)], 8, FunnelEditorvue_type_template_id_56111fec_hoisted_28), _ctx.form.steps.length >= _ctx.maxSteps ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", FunnelEditorvue_type_template_id_56111fec_hoisted_30, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_MaxStepsReached', _ctx.maxSteps)), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h3", _hoisted_31, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_TestUrl')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", _hoisted_32, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_TestUrlHelp')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
      type: "text",
      class: "funnelEditor__testUrl browser-default",
      name: "testUrl",
      "onUpdate:modelValue": _cache[6] || (_cache[6] = $event => _ctx.testUrl = $event),
      placeholder: "https://example.org/cart",
      "aria-label": _ctx.translate('RebelFunnels_TestUrl')
    }, null, 8, _hoisted_33), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelText"], _ctx.testUrl]]), _ctx.testUrl && _ctx.isTested && !_ctx.testResult.includes(true) ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", _hoisted_34, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_MatchesNoStep')), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h3", _hoisted_35, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_SegmentFilter')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", _hoisted_36, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_SegmentFilterHelp')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_37, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_SegmentGenerator, {
      modelValue: _ctx.form.segmentFilter,
      "onUpdate:modelValue": _cache[7] || (_cache[7] = $event => _ctx.form.segmentFilter = $event)
    }, null, 8, ["modelValue"])]), _ctx.isSuperUser && !_ctx.funnel ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_38, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_Field, {
      uicontrol: "checkbox",
      name: "allWebsites",
      modelValue: _ctx.form.allWebsites,
      "onUpdate:modelValue": _cache[8] || (_cache[8] = $event => _ctx.form.allWebsites = $event),
      "full-width": true,
      title: _ctx.translate('RebelFunnels_AvailableForAllWebsites'),
      "inline-help": _ctx.translate('RebelFunnels_AvailableForAllWebsitesHelp')
    }, null, 8, ["modelValue", "title", "inline-help"])])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.changesData && _ctx.rearchiveMonths > 0 ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", _hoisted_39, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_Notification, {
      context: "warning",
      noclear: true
    }, {
      default: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withCtx"])(() => [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_UpdateWarning', _ctx.rearchiveMonths)), 1)]),
      _: 1
    })])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_40, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_41, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_SaveButton, {
      saving: _ctx.isSaving,
      disabled: !_ctx.canSave,
      value: _ctx.translate('General_Save'),
      onConfirm: _cache[9] || (_cache[9] = $event => _ctx.save())
    }, null, 8, ["saving", "disabled", "value"])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
      type: "button",
      class: "funnelEditor__cancel",
      onClick: _cache[10] || (_cache[10] = $event => _ctx.$emit('cancel'))
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('General_Cancel')), 1)])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", _hoisted_42, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h3", _hoisted_43, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_Preview')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", _hoisted_44, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_PreviewHint')), 1), !_ctx.stepsAreComplete ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", _hoisted_45, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_PreviewNeedsSteps')), 1)) : _ctx.previewError ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", _hoisted_46, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.previewError), 1)) : !_ctx.preview ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("p", _hoisted_47, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", _hoisted_48, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_MatomoLoader), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('General_LoadingData')), 1)])])) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_FunnelChart, {
      key: 3,
      steps: _ctx.preview,
      currency: _ctx.currency
    }, null, 8, ["steps", "currency"]))])], 32)]),
    _: 1
  }, 8, ["content-title"])]);
}
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/FunnelEditor/FunnelEditor.vue?vue&type=template&id=56111fec

// EXTERNAL MODULE: external "CorePluginsAdmin"
var external_CorePluginsAdmin_ = __webpack_require__("a5a2");

// EXTERNAL MODULE: external "SegmentEditor"
var external_SegmentEditor_ = __webpack_require__("f06f");

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/RebelFunnels/vue/src/FunnelEditor/FunnelEditor.vue?vue&type=script&lang=ts





// Rule type => the segment whose most used values are suggested for it.
const SUGGESTION_SEGMENTS = {
  url: 'pageUrl',
  title: 'pageTitle',
  event_category: 'eventCategory',
  event_action: 'eventAction',
  event_name: 'eventName',
  download: 'downloadUrl',
  outlink: 'outlinkUrl',
  search: 'siteSearchKeyword'
};
// The rule types that are matched by a form of the plugin RebelMyForms, by the name of the form.
const FORM_TYPES = ['form_start', 'form_submit', 'form_success'];
const FORM_SEGMENT = 'formName';
const NOTIFICATION_ID = 'rebelFunnelsSaved';
const TYPING_PAUSE = 600;
let lastKey = 0;
function newStep(step) {
  lastKey += 1;
  return {
    key: lastKey,
    name: step ? external_CoreHome_["Matomo"].helper.htmlDecode(step.name) : '',
    direct: step ? !!step.direct : false,
    rules: step ? step.rules.map(rule => Object.assign({}, rule)) : [{
      type: 'url',
      operator: 'contains',
      value: ''
    }]
  };
}
// The part of the steps that decides what is counted.
function rulesOf(steps) {
  return JSON.stringify(steps.map((step, index) => [index > 0 && !!step.direct, step.rules.map(rule => [rule.type, rule.operator, rule.value.trim()])]));
}
/* harmony default export */ var FunnelEditorvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  props: {
    funnel: {
      type: Object,
      default: null
    },
    // A funnel that is not saved, to start a new one from.
    template: {
      type: Object,
      default: null
    },
    currency: {
      type: String,
      default: ''
    },
    rearchiveMonths: {
      type: Number,
      default: 0
    },
    maxSteps: {
      type: Number,
      default: 10
    }
  },
  emits: ['saved', 'cancel'],
  components: {
    ContentBlock: external_CoreHome_["ContentBlock"],
    Field: external_CorePluginsAdmin_["Field"],
    FunnelChart: FunnelChart,
    MatomoLoader: external_CoreHome_["MatomoLoader"],
    Notification: external_CoreHome_["Notification"],
    SaveButton: external_CorePluginsAdmin_["SaveButton"],
    SegmentGenerator: external_SegmentEditor_["SegmentGenerator"]
  },
  data() {
    const {
      funnel,
      template
    } = this;
    const source = funnel || template;
    return {
      form: {
        name: source ? external_CoreHome_["Matomo"].helper.htmlDecode(source.name) : '',
        description: funnel ? external_CoreHome_["Matomo"].helper.htmlDecode(funnel.description) : '',
        steps: source ? source.steps.map(step => newStep(step)) : [newStep(), newStep()],
        segmentFilter: funnel ? funnel.segment_filter : '',
        allWebsites: funnel ? funnel.idsite === 0 : false
      },
      isSaving: false,
      grabbed: null,
      dragged: null,
      suggestions: {},
      siteGoals: [],
      // The names of the forms of the website, or null when forms are not tracked.
      formNames: null,
      testUrl: '',
      testResult: [],
      isTested: false,
      preview: null,
      previewError: '',
      timer: 0,
      requests: 0
    };
  },
  computed: {
    title() {
      return this.funnel ? Object(external_CoreHome_["translate"])('RebelFunnels_EditFunnel', external_CoreHome_["Matomo"].helper.htmlDecode(this.funnel.name)) : Object(external_CoreHome_["translate"])('RebelFunnels_CreateNewFunnel');
    },
    isSuperUser() {
      return external_CoreHome_["Matomo"].hasSuperUserAccess;
    },
    ruleTypes() {
      return [...Object.keys(SUGGESTION_SEGMENTS), 'goal', ...(this.formNames ? FORM_TYPES : [])];
    },
    // What a goal rule can be matched by. A funnel for all websites has no goals in common.
    goals() {
      return [...(this.form.allWebsites ? [] : this.siteGoals), {
        value: 'order',
        name: Object(external_CoreHome_["translate"])('RebelFunnels_GoalOrder')
      }, {
        value: 'cart',
        name: Object(external_CoreHome_["translate"])('RebelFunnels_GoalCart')
      }];
    },
    ruleOperators() {
      return ['equals', 'contains', 'starts_with', 'ends_with', 'regex'];
    },
    steps() {
      return this.form.steps.map(step => ({
        name: step.name,
        rules: step.rules,
        direct: step.direct
      }));
    },
    // Booleans are sent as 1 or 0.
    stepsToSend() {
      return this.steps.map((step, index) => Object.assign(Object.assign({}, step), {}, {
        direct: index > 0 && step.direct ? 1 : 0
      }));
    },
    stepsAreComplete() {
      return this.steps.every(step => step.name.trim() && step.rules.every(rule => rule.value.trim()));
    },
    canSave() {
      return !!this.form.name.trim() && this.stepsAreComplete;
    },
    changesData() {
      const {
        funnel
      } = this;
      return !!funnel && (rulesOf(this.steps) !== rulesOf(funnel.steps) || this.form.segmentFilter !== funnel.segment_filter);
    },
    // What the preview and the URL test depend on.
    counted() {
      return JSON.stringify([rulesOf(this.steps), this.form.segmentFilter, this.testUrl]);
    }
  },
  created() {
    this.$watch('counted', () => {
      window.clearTimeout(this.timer);
      this.timer = window.setTimeout(() => this.count(), TYPING_PAUSE);
    });
    this.count();
    external_CoreHome_["AjaxHelper"].fetch({
      method: 'Goals.getGoals',
      filter_limit: '-1'
    }, {
      createErrorNotification: false
    }).then(goals => {
      this.siteGoals = Object.values(goals).map(goal => ({
        value: String(goal.idgoal),
        name: external_CoreHome_["Matomo"].helper.htmlDecode(goal.name)
      }));
    }).catch(() => {
      // Without the Goals plugin only orders and carts can be chosen.
    });
    external_CoreHome_["AjaxHelper"].fetch({
      method: 'API.getSuggestedValuesForSegment',
      segmentName: FORM_SEGMENT
    }, {
      createErrorNotification: false
    }).then(names => {
      this.formNames = names.map(name => external_CoreHome_["Matomo"].helper.htmlDecode(name));
    }).catch(() => {
      // Without the plugin RebelMyForms a step cannot be a form.
    });
  },
  beforeUnmount() {
    window.clearTimeout(this.timer);
  },
  methods: {
    addStep() {
      this.form.steps.push(newStep());
    },
    addRule(step) {
      step.rules.push({
        type: step.rules[0].type,
        operator: 'contains',
        value: ''
      });
    },
    // A goal is chosen from a list, the other types are compared with what is typed.
    onTypeChange(rule) {
      const isGoalValue = this.goals.some(goal => goal.value === rule.value);
      if (rule.type === 'goal' !== isGoalValue) {
        rule.value = '';
      }
      if (rule.type === 'goal') {
        rule.operator = 'equals';
      }
    },
    moveStep(from, to) {
      if (from === null || from === to || to < 0 || to >= this.form.steps.length) {
        return;
      }
      this.form.steps.splice(to, 0, this.form.steps.splice(from, 1)[0]);
      this.dragged = null;
    },
    // The values to suggest. Without them the value can still be typed.
    fetchSuggestions(type) {
      if (this.suggestions[type]) {
        return;
      }
      if (FORM_TYPES.includes(type)) {
        this.suggestions[type] = this.formNames || [];
        return;
      }
      this.suggestions[type] = [];
      external_CoreHome_["AjaxHelper"].fetch({
        method: 'API.getSuggestedValuesForSegment',
        segmentName: SUGGESTION_SEGMENTS[type]
      }, {
        createErrorNotification: false
      }).then(values => {
        this.suggestions[type] = values.map(value => external_CoreHome_["Matomo"].helper.htmlDecode(value));
      }).catch(() => {
        // No suggestions.
      });
    },
    // Counts the steps for the preview and tests the URL, as far as the steps are filled in.
    count() {
      this.requests += 1;
      const request = this.requests;
      this.preview = null;
      this.previewError = '';
      this.testResult = [];
      this.isTested = false;
      if (!this.stepsAreComplete) {
        return;
      }
      const options = {
        createErrorNotification: false
      };
      external_CoreHome_["AjaxHelper"].post({
        method: 'RebelFunnels.previewFunnel'
      }, {
        steps: this.stepsToSend,
        segmentFilter: this.form.segmentFilter
      }, options).then(preview => {
        if (request === this.requests) {
          this.preview = preview;
        }
      }).catch(error => {
        if (request === this.requests) {
          this.previewError = error.message || String(error);
        }
      });
      if (this.testUrl.trim()) {
        external_CoreHome_["AjaxHelper"].post({
          method: 'RebelFunnels.testUrl'
        }, {
          steps: this.stepsToSend,
          url: this.testUrl
        }, options).then(result => {
          if (request === this.requests) {
            this.testResult = result;
            this.isTested = true;
          }
        }).catch(() => {
          // The preview shows what is wrong with the steps.
        });
      }
    },
    save() {
      if (!this.canSave || this.isSaving) {
        return;
      }
      this.isSaving = true;
      const params = {
        method: this.funnel ? 'RebelFunnels.updateFunnel' : 'RebelFunnels.addFunnel'
      };
      if (this.funnel) {
        params.idFunnel = this.funnel.idfunnel;
      }
      external_CoreHome_["AjaxHelper"].post(params, {
        name: this.form.name,
        description: this.form.description,
        steps: this.stepsToSend,
        segmentFilter: this.form.segmentFilter,
        allWebsites: this.form.allWebsites ? 1 : 0
      }).then(() => {
        external_CoreHome_["NotificationsStore"].show({
          id: NOTIFICATION_ID,
          message: Object(external_CoreHome_["translate"])('RebelFunnels_FunnelSaved'),
          context: 'success',
          type: 'transient'
        });
        this.$emit('saved');
      }).finally(() => {
        this.isSaving = false;
      });
    }
  }
}));
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/FunnelEditor/FunnelEditor.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/FunnelEditor/FunnelEditor.vue



FunnelEditorvue_type_script_lang_ts.render = FunnelEditorvue_type_template_id_56111fec_render

/* harmony default export */ var FunnelEditor = (FunnelEditorvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/RebelFunnels/vue/src/FunnelsList/FunnelsList.vue?vue&type=template&id=7925ab6c

const FunnelsListvue_type_template_id_7925ab6c_hoisted_1 = {
  class: "funnelsList"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_2 = {
  class: "loadingPiwik"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_3 = ["placeholder", "aria-label"];
const FunnelsListvue_type_template_id_7925ab6c_hoisted_4 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, null, -1);
const FunnelsListvue_type_template_id_7925ab6c_hoisted_5 = {
  key: 0
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_6 = {
  colspan: "8"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_7 = ["draggable", "onDragstart", "onDrop"];
const FunnelsListvue_type_template_id_7925ab6c_hoisted_8 = {
  class: "funnelsList__handleCell"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_9 = ["title", "disabled", "onKeydown"];
const FunnelsListvue_type_template_id_7925ab6c_hoisted_10 = {
  class: "funnelsList__nameCell"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_11 = ["href", "title"];
const FunnelsListvue_type_template_id_7925ab6c_hoisted_12 = {
  key: 0,
  class: "funnelsList__chip"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_13 = {
  key: 1,
  class: "funnelsList__description"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_14 = {
  key: 0,
  class: "funnelsList__date"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_15 = {
  key: 1,
  class: "funnelsList__none"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_16 = {
  key: 2,
  class: "funnelsList__description"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_17 = {
  class: "funnelsList__date"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_18 = {
  class: "funnelsList__actions"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_19 = ["title", "disabled", "onClick"];
const FunnelsListvue_type_template_id_7925ab6c_hoisted_20 = ["title", "disabled", "onClick"];
const FunnelsListvue_type_template_id_7925ab6c_hoisted_21 = ["title", "onClick"];
const FunnelsListvue_type_template_id_7925ab6c_hoisted_22 = ["title", "disabled", "onClick"];
const FunnelsListvue_type_template_id_7925ab6c_hoisted_23 = {
  key: 0,
  class: "funnelsList__duplicate"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_24 = {
  class: "tableActionBar"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_25 = ["disabled"];
const FunnelsListvue_type_template_id_7925ab6c_hoisted_26 = /*#__PURE__*/Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", {
  class: "icon-add"
}, null, -1);
const FunnelsListvue_type_template_id_7925ab6c_hoisted_27 = {
  class: "funnelsList__examples"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_28 = ["onClick"];
const FunnelsListvue_type_template_id_7925ab6c_hoisted_29 = ["href"];
const FunnelsListvue_type_template_id_7925ab6c_hoisted_30 = {
  class: "ui-confirm",
  ref: "confirm"
};
const FunnelsListvue_type_template_id_7925ab6c_hoisted_31 = ["value"];
const FunnelsListvue_type_template_id_7925ab6c_hoisted_32 = ["value"];
function FunnelsListvue_type_template_id_7925ab6c_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_MatomoLoader = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("MatomoLoader");
  const _component_FunnelDuplicateForm = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("FunnelDuplicateForm");
  const _component_ContentBlock = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("ContentBlock");
  const _directive_content_table = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveDirective"])("content-table");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", FunnelsListvue_type_template_id_7925ab6c_hoisted_1, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_ContentBlock, null, {
    default: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withCtx"])(() => [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_ManageFunnelsIntro')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", FunnelsListvue_type_template_id_7925ab6c_hoisted_2, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createVNode"])(_component_MatomoLoader), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('General_LoadingData')), 1)])], 512), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vShow"], _ctx.isLoading]]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
      type: "search",
      class: "funnelsList__search browser-default",
      "onUpdate:modelValue": _cache[0] || (_cache[0] = $event => _ctx.search = $event),
      placeholder: _ctx.translate('General_Search'),
      "aria-label": _ctx.translate('General_Search')
    }, null, 8, FunnelsListvue_type_template_id_7925ab6c_hoisted_3), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vModelText"], _ctx.search], [external_commonjs_vue_commonjs2_vue_root_Vue_["vShow"], !_ctx.isLoading && _ctx.funnels.length > 1]]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])((Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("table", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("thead", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("tr", null, [FunnelsListvue_type_template_id_7925ab6c_hoisted_4, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('General_Id')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('General_Name')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_Steps')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_AvailableFor')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_ArchivedData')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_Updated')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("th", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('General_Actions')), 1)])]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("tbody", null, [!_ctx.visibleFunnels.length ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("tr", FunnelsListvue_type_template_id_7925ab6c_hoisted_5, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", FunnelsListvue_type_template_id_7925ab6c_hoisted_6, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate(_ctx.funnels.length ? 'General_SearchNoResults' : 'RebelFunnels_NoFunnelsYet')), 1)])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.visibleFunnels, funnel => {
      return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("tr", {
        key: funnel.idfunnel,
        draggable: _ctx.canManage(funnel),
        onDragstart: $event => _ctx.dragged = funnel,
        onDragover: _cache[1] || (_cache[1] = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])(() => {}, ["prevent"])),
        onDrop: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])($event => _ctx.move(_ctx.dragged, funnel), ["prevent"])
      }, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", FunnelsListvue_type_template_id_7925ab6c_hoisted_8, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
        type: "button",
        class: "table-action icon-menu-hamburger funnelsList__handle",
        title: _ctx.translate('RebelFunnels_Reorder'),
        disabled: !_ctx.canManage(funnel),
        onKeydown: [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withKeys"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])($event => _ctx.moveBy(funnel, -1), ["prevent"]), ["up"]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withKeys"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withModifiers"])($event => _ctx.moveBy(funnel, 1), ["prevent"]), ["down"])]
      }, null, 40, FunnelsListvue_type_template_id_7925ab6c_hoisted_9)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(funnel.idfunnel), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", FunnelsListvue_type_template_id_7925ab6c_hoisted_10, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("a", {
        class: "funnelsList__name",
        href: _ctx.funnelUrl(funnel),
        title: _ctx.translate('RebelFunnels_ViewFunnel')
      }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.decode(funnel.name)), 9, FunnelsListvue_type_template_id_7925ab6c_hoisted_11), funnel.paused ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", FunnelsListvue_type_template_id_7925ab6c_hoisted_12, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_Paused')), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), funnel.description ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", FunnelsListvue_type_template_id_7925ab6c_hoisted_13, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.decode(funnel.description)), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(funnel.steps, (step, index) => {
        return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", {
          class: "funnelsList__chip",
          key: index
        }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(index + 1) + ". " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.decode(step.name)), 1);
      }), 128))]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(funnel.idsite === 0 ? _ctx.translate('RebelFunnels_AllWebsites') : _ctx.translate('RebelFunnels_ThisWebsite')), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [_ctx.statusById[funnel.idfunnel] ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], {
        key: 0
      }, [_ctx.statusById[funnel.idfunnel].first_date ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", FunnelsListvue_type_template_id_7925ab6c_hoisted_14, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.statusById[funnel.idfunnel].first_date) + " – " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.statusById[funnel.idfunnel].last_date), 1)) : (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", FunnelsListvue_type_template_id_7925ab6c_hoisted_15, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_NoArchivedData')), 1)), _ctx.statusById[funnel.idfunnel].is_queued ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("span", FunnelsListvue_type_template_id_7925ab6c_hoisted_16, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.statusById[funnel.idfunnel].queued_days ? _ctx.translate('RebelFunnels_DaysWaiting', _ctx.statusById[funnel.idfunnel].queued_days) : _ctx.translate('RebelFunnels_WaitingToBeArchived')), 1)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)], 64)) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", null, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("span", FunnelsListvue_type_template_id_7925ab6c_hoisted_17, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(funnel.updated_date.slice(0, 10)), 1)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("td", FunnelsListvue_type_template_id_7925ab6c_hoisted_18, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
        type: "button",
        class: Object(external_commonjs_vue_commonjs2_vue_root_Vue_["normalizeClass"])(["table-action", funnel.paused ? 'icon-play' : 'icon-pause']),
        title: _ctx.translate(funnel.paused ? 'RebelFunnels_Resume' : 'RebelFunnels_Pause'),
        disabled: !_ctx.canManage(funnel),
        onClick: $event => _ctx.setPaused(funnel, !funnel.paused)
      }, null, 10, FunnelsListvue_type_template_id_7925ab6c_hoisted_19), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
        type: "button",
        class: "table-action icon-edit",
        title: _ctx.translate('General_Edit'),
        disabled: !_ctx.canManage(funnel),
        onClick: $event => _ctx.$emit('edit', funnel)
      }, null, 8, FunnelsListvue_type_template_id_7925ab6c_hoisted_20), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
        type: "button",
        class: "table-action icon-content-copy",
        title: _ctx.translate('RebelFunnels_Duplicate'),
        onClick: $event => _ctx.duplicated = funnel
      }, null, 8, FunnelsListvue_type_template_id_7925ab6c_hoisted_21), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
        type: "button",
        class: "table-action icon-delete",
        title: _ctx.translate('General_Delete'),
        disabled: !_ctx.canManage(funnel),
        onClick: $event => _ctx.confirmDelete(funnel)
      }, null, 8, FunnelsListvue_type_template_id_7925ab6c_hoisted_22)])], 40, FunnelsListvue_type_template_id_7925ab6c_hoisted_7);
    }), 128))])])), [[_directive_content_table], [external_commonjs_vue_commonjs2_vue_root_Vue_["vShow"], !_ctx.isLoading]]), _ctx.duplicated ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", FunnelsListvue_type_template_id_7925ab6c_hoisted_23, [(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_FunnelDuplicateForm, {
      key: _ctx.duplicated.idfunnel,
      "item-name": _ctx.decode(_ctx.duplicated.name),
      "is-busy": _ctx.isDuplicating,
      onConfirm: _cache[2] || (_cache[2] = $event => _ctx.duplicate($event)),
      onCancel: _cache[3] || (_cache[3] = $event => _ctx.duplicated = null)
    }, null, 8, ["item-name", "is-busy"]))])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", FunnelsListvue_type_template_id_7925ab6c_hoisted_24, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("button", {
      type: "button",
      class: "btn",
      disabled: _ctx.isLoading,
      onClick: _cache[4] || (_cache[4] = $event => _ctx.$emit('create', null))
    }, [FunnelsListvue_type_template_id_7925ab6c_hoisted_26, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" " + Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_CreateNewFunnel')), 1)], 8, FunnelsListvue_type_template_id_7925ab6c_hoisted_25)]), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["withDirectives"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("p", FunnelsListvue_type_template_id_7925ab6c_hoisted_27, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_StartFromExample')) + " ", 1), (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(true), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])(external_commonjs_vue_commonjs2_vue_root_Vue_["Fragment"], null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["renderList"])(_ctx.examples, example => {
      return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("button", {
        type: "button",
        class: "funnelsList__example",
        key: example.name,
        onClick: $event => _ctx.$emit('create', example)
      }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(example.name), 9, FunnelsListvue_type_template_id_7925ab6c_hoisted_28);
    }), 128)), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createTextVNode"])(" · "), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("a", {
      href: _ctx.documentationUrl
    }, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_Documentation')), 9, FunnelsListvue_type_template_id_7925ab6c_hoisted_29)], 512), [[external_commonjs_vue_commonjs2_vue_root_Vue_["vShow"], !_ctx.isLoading]])]),
    _: 1
  }), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("div", FunnelsListvue_type_template_id_7925ab6c_hoisted_30, [Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("h2", null, Object(external_commonjs_vue_commonjs2_vue_root_Vue_["toDisplayString"])(_ctx.translate('RebelFunnels_DeleteFunnelConfirm', _ctx.funnelToDeleteName)), 1), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    role: "yes",
    type: "button",
    value: _ctx.translate('General_Yes')
  }, null, 8, FunnelsListvue_type_template_id_7925ab6c_hoisted_31), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementVNode"])("input", {
    role: "no",
    type: "button",
    value: _ctx.translate('General_No')
  }, null, 8, FunnelsListvue_type_template_id_7925ab6c_hoisted_32)], 512)]);
}
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/FunnelsList/FunnelsList.vue?vue&type=template&id=7925ab6c

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/RebelFunnels/vue/src/FunnelsList/FunnelsList.vue?vue&type=script&lang=ts



/* harmony default export */ var FunnelsListvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  props: {
    funnels: {
      type: Array,
      required: true
    },
    archiveStatus: {
      type: Array,
      required: true
    },
    isLoading: Boolean
  },
  emits: ['create', 'edit', 'changed'],
  components: {
    ContentBlock: external_CoreHome_["ContentBlock"],
    FunnelDuplicateForm: FunnelDuplicateForm,
    MatomoLoader: external_CoreHome_["MatomoLoader"]
  },
  directives: {
    ContentTable: external_CoreHome_["ContentTable"]
  },
  data() {
    return {
      funnelToDeleteName: '',
      dragged: null,
      duplicated: null,
      isDuplicating: false,
      search: ''
    };
  },
  computed: {
    documentationUrl() {
      return `?${external_CoreHome_["MatomoUrl"].stringify({
        module: 'RebelFunnels',
        action: 'documentation'
      })}`;
    },
    // Funnels that make sense on any website, to change into one's own.
    examples() {
      const anyPage = {
        type: 'url',
        operator: 'regex',
        value: '.'
      };
      const pageStep = name => ({
        name: Object(external_CoreHome_["translate"])(name),
        rules: [Object.assign({}, anyPage)]
      });
      return [{
        name: Object(external_CoreHome_["translate"])('RebelFunnels_ExampleDepth'),
        steps: [pageStep('RebelFunnels_ExampleFirstPage'), Object.assign(Object.assign({}, pageStep('RebelFunnels_ExampleSecondPage')), {}, {
          direct: false
        }), Object.assign(Object.assign({}, pageStep('RebelFunnels_ExampleThirdPage')), {}, {
          direct: false
        })]
      }, {
        name: Object(external_CoreHome_["translate"])('RebelFunnels_ExampleOrder'),
        steps: [pageStep('RebelFunnels_ExampleFirstPage'), {
          name: Object(external_CoreHome_["translate"])('RebelFunnels_GoalCart'),
          rules: [{
            type: 'goal',
            operator: 'equals',
            value: 'cart'
          }]
        }, {
          name: Object(external_CoreHome_["translate"])('RebelFunnels_GoalOrder'),
          rules: [{
            type: 'goal',
            operator: 'equals',
            value: 'order'
          }]
        }]
      }];
    },
    visibleFunnels() {
      const words = this.search.toLowerCase().split(/\s+/).filter(word => word);
      return this.funnels.filter(funnel => {
        const text = [this.decode(funnel.name), this.decode(funnel.description), ...funnel.steps.map(step => this.decode(step.name))].join(' ').toLowerCase();
        return words.every(word => text.includes(word));
      });
    },
    statusById() {
      return Object.fromEntries(this.archiveStatus.map(status => [status.idfunnel, status]));
    }
  },
  methods: {
    decode(text) {
      return external_CoreHome_["Matomo"].helper.htmlDecode(text);
    },
    canManage(funnel) {
      return funnel.idsite !== 0 || external_CoreHome_["Matomo"].hasSuperUserAccess;
    },
    funnelUrl(funnel) {
      const query = external_CoreHome_["MatomoUrl"].stringify(Object.assign(Object.assign({}, external_CoreHome_["MatomoUrl"].hashParsed.value), {}, {
        category: 'RebelFunnels_Funnels',
        subcategory: `RebelFunnels_funnel_${funnel.idfunnel}`
      }));
      return `#?${query}`;
    },
    moveBy(funnel, step) {
      const movable = this.funnels.filter(other => this.canManage(other));
      this.move(funnel, movable[movable.indexOf(funnel) + step] || null);
    },
    move(funnel, target) {
      if (!funnel || !target || funnel === target || !this.canManage(target)) {
        return;
      }
      const movable = this.funnels.filter(other => this.canManage(other));
      movable.splice(movable.indexOf(target), 0, movable.splice(movable.indexOf(funnel), 1)[0]);
      // Funnels the user cannot manage keep their place.
      const ordered = this.funnels.map(other => this.canManage(other) ? movable.shift() : other);
      external_CoreHome_["AjaxHelper"].post({
        method: 'RebelFunnels.setFunnelOrder'
      }, {
        idFunnels: ordered.map(other => other.idfunnel)
      }).then(() => {
        this.$emit('changed');
      });
    },
    setPaused(funnel, paused) {
      external_CoreHome_["AjaxHelper"].post({
        method: paused ? 'RebelFunnels.pauseFunnel' : 'RebelFunnels.resumeFunnel',
        idFunnel: funnel.idfunnel
      }).then(() => {
        this.$emit('changed');
      });
    },
    duplicate(site) {
      const funnel = this.duplicated;
      if (!funnel) {
        return;
      }
      this.isDuplicating = true;
      external_CoreHome_["AjaxHelper"].post({
        method: 'RebelFunnels.duplicateFunnel',
        idFunnel: funnel.idfunnel
      }, {
        idDestinationSite: site.id
      }).then(() => {
        external_CoreHome_["NotificationsStore"].show({
          id: 'rebelFunnelsDuplicated',
          message: Object(external_CoreHome_["translate"])('RebelFunnels_DuplicatedTo', this.decode(funnel.name), site.name),
          context: 'success',
          type: 'transient'
        });
        this.duplicated = null;
        this.$emit('changed');
      }).finally(() => {
        this.isDuplicating = false;
      });
    },
    confirmDelete(funnel) {
      this.funnelToDeleteName = this.decode(funnel.name);
      external_CoreHome_["Matomo"].helper.modalConfirm(this.$refs.confirm, {
        yes: () => {
          external_CoreHome_["AjaxHelper"].post({
            method: 'RebelFunnels.deleteFunnel',
            idFunnel: funnel.idfunnel
          }).then(() => {
            this.$emit('changed');
          });
        }
      });
    }
  }
}));
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/FunnelsList/FunnelsList.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/FunnelsList/FunnelsList.vue



FunnelsListvue_type_script_lang_ts.render = FunnelsListvue_type_template_id_7925ab6c_render

/* harmony default export */ var FunnelsList = (FunnelsListvue_type_script_lang_ts);
// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-babel/node_modules/cache-loader/dist/cjs.js??ref--13-0!./node_modules/@vue/cli-plugin-babel/node_modules/thread-loader/dist/cjs.js!./node_modules/babel-loader/lib!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist/templateLoader.js??ref--6!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/RebelFunnels/vue/src/ManageFunnels/ManageFunnels.vue?vue&type=template&id=92fd4b3c

const ManageFunnelsvue_type_template_id_92fd4b3c_hoisted_1 = {
  class: "manageFunnels"
};
function ManageFunnelsvue_type_template_id_92fd4b3c_render(_ctx, _cache, $props, $setup, $data, $options) {
  const _component_FunnelsList = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("FunnelsList");
  const _component_FunnelEditor = Object(external_commonjs_vue_commonjs2_vue_root_Vue_["resolveComponent"])("FunnelEditor");
  return Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createElementBlock"])("div", ManageFunnelsvue_type_template_id_92fd4b3c_hoisted_1, [!_ctx.isEditing ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_FunnelsList, {
    key: 0,
    funnels: _ctx.funnels,
    "archive-status": _ctx.archiveStatus,
    "is-loading": _ctx.isLoading,
    onCreate: _cache[0] || (_cache[0] = $event => _ctx.edit(null, $event)),
    onEdit: _cache[1] || (_cache[1] = $event => _ctx.edit($event)),
    onChanged: _cache[2] || (_cache[2] = $event => _ctx.onChanged())
  }, null, 8, ["funnels", "archive-status", "is-loading"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true), _ctx.isEditing ? (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["openBlock"])(), Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createBlock"])(_component_FunnelEditor, {
    key: 1,
    funnel: _ctx.editedFunnel,
    template: _ctx.template,
    currency: _ctx.currency,
    "rearchive-months": _ctx.rearchiveMonths,
    "max-steps": _ctx.maxSteps,
    onSaved: _cache[3] || (_cache[3] = $event => _ctx.onChanged()),
    onCancel: _cache[4] || (_cache[4] = $event => _ctx.isEditing = false)
  }, null, 8, ["funnel", "template", "currency", "rearchive-months", "max-steps"])) : Object(external_commonjs_vue_commonjs2_vue_root_Vue_["createCommentVNode"])("", true)]);
}
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/ManageFunnels/ManageFunnels.vue?vue&type=template&id=92fd4b3c

// CONCATENATED MODULE: ./node_modules/@vue/cli-plugin-typescript/node_modules/cache-loader/dist/cjs.js??ref--15-0!./node_modules/babel-loader/lib!./node_modules/@vue/cli-plugin-typescript/node_modules/ts-loader??ref--15-2!./node_modules/@vue/cli-service/node_modules/cache-loader/dist/cjs.js??ref--1-0!./node_modules/@vue/cli-service/node_modules/vue-loader-v16/dist??ref--1-1!./plugins/RebelFunnels/vue/src/ManageFunnels/ManageFunnels.vue?vue&type=script&lang=ts




/* harmony default export */ var ManageFunnelsvue_type_script_lang_ts = (Object(external_commonjs_vue_commonjs2_vue_root_Vue_["defineComponent"])({
  props: {
    rearchiveMonths: {
      type: Number,
      default: 0
    },
    maxSteps: {
      type: Number,
      default: 10
    },
    currency: {
      type: String,
      default: ''
    }
  },
  components: {
    FunnelsList: FunnelsList,
    FunnelEditor: FunnelEditor
  },
  data() {
    return {
      isLoading: true,
      isEditing: false,
      editedFunnel: null,
      template: null,
      funnels: [],
      archiveStatus: []
    };
  },
  created() {
    this.fetchFunnels().finally(() => {
      this.isLoading = false;
    });
  },
  methods: {
    fetchFunnels() {
      // Not waited for: it reads the archive tables, which can take longer than the list.
      external_CoreHome_["AjaxHelper"].fetch({
        method: 'RebelFunnels.getArchiveStatus',
        filter_limit: '-1'
      }).then(archiveStatus => {
        this.archiveStatus = archiveStatus;
      });
      return external_CoreHome_["AjaxHelper"].fetch({
        method: 'RebelFunnels.getFunnels',
        filter_limit: '-1'
      }).then(funnels => {
        this.funnels = funnels;
      });
    },
    edit(funnel, template = null) {
      this.editedFunnel = funnel;
      this.template = template;
      this.isEditing = true;
      external_CoreHome_["Matomo"].helper.lazyScrollToContent();
    },
    onChanged() {
      this.isEditing = false;
      this.fetchFunnels();
      // The funnels are pages of the reporting menu.
      external_CoreHome_["ReportingMenuStore"].reloadMenuItems();
    }
  }
}));
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/ManageFunnels/ManageFunnels.vue?vue&type=script&lang=ts
 
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/ManageFunnels/ManageFunnels.vue



ManageFunnelsvue_type_script_lang_ts.render = ManageFunnelsvue_type_template_id_92fd4b3c_render

/* harmony default export */ var ManageFunnels = (ManageFunnelsvue_type_script_lang_ts);
// CONCATENATED MODULE: ./plugins/RebelFunnels/vue/src/index.ts





// CONCATENATED MODULE: ./node_modules/@vue/cli-service/lib/commands/build/entry-lib-no-default.js




/***/ })

/******/ });
});
//# sourceMappingURL=RebelFunnels.umd.js.map