(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory();
	else if(typeof define === 'function' && define.amd)
		define([], factory);
	else if(typeof exports === 'object')
		exports["Drawflow"] = factory();
	else
		root["Drawflow"] = factory();
})((typeof self !== 'undefined' ? self : this), function() {
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
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./assets_src/backend/js/multistep.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./assets_src/backend/js/multistep.js":
/*!********************************************!*\
  !*** ./assets_src/backend/js/multistep.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(e, r, t) { return (r = _toPropertyKey(r)) in e ? Object.defineProperty(e, r, { value: t, enumerable: !0, configurable: !0, writable: !0 }) : e[r] = t, e; }
function _toConsumableArray(r) { return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _iterableToArray(r) { if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r); }
function _arrayWithoutHoles(r) { if (Array.isArray(r)) return _arrayLikeToArray(r); }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
var Drawflow = /*#__PURE__*/function () {
  function Drawflow(container) {
    var render = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
    var parent = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
    _classCallCheck(this, Drawflow);
    this.events = {};
    this.container = container;
    this.precanvas = null;
    this.nodeId = 1;
    this.ele_selected = null;
    this.node_selected = null;
    this.drag = false;
    this.reroute = false;
    this.reroute_fix_curvature = false;
    this.curvature = 0.5;
    this.reroute_curvature_start_end = 0.5;
    this.reroute_curvature = 0.5;
    this.reroute_width = 6;
    this.drag_point = false;
    this.editor_selected = false;
    this.connection = false;
    this.connection_ele = null;
    this.connection_selected = null;
    this.canvas_x = 0;
    this.canvas_y = 0;
    this.pos_x = 0;
    this.pos_x_start = 0;
    this.pos_y = 0;
    this.pos_y_start = 0;
    this.mouse_x = 0;
    this.mouse_y = 0;
    this.line_path = 5;
    this.first_click = null;
    this.force_first_input = false;
    this.draggable_inputs = true;
    this.useuuid = false;
    this.parent = parent;
    this.noderegister = {};
    this.render = render;
    this.drawflow = {
      drawflow: {
        Home: {
          data: {}
        }
      }
    };
    // Configurable options
    this.module = 'Home';
    this.editor_mode = 'edit';
    this.zoom = 1;
    this.zoom_max = 1.6;
    this.zoom_min = 0.5;
    this.zoom_value = 0.1;
    this.zoom_last_value = 1;

    // Mobile
    this.evCache = new Array();
    this.prevDiff = -1;
  }
  return _createClass(Drawflow, [{
    key: "showExport",
    value: function showExport() {
      console.log(JSON.stringify(this.export(), null, 4));
    }
  }, {
    key: "start",
    value: function start() {
      // console.info("Start Drawflow!!");
      this.container.classList.add('parent-drawflow');
      this.container.tabIndex = 0;
      this.precanvas = document.createElement('div');
      this.precanvas.classList.add('drawflow');
      this.container.appendChild(this.precanvas);

      /* Mouse and Touch Actions */
      this.container.addEventListener('mouseup', this.dragEnd.bind(this));
      this.container.addEventListener('mousemove', this.position.bind(this));
      this.container.addEventListener('mousedown', this.click.bind(this));
      this.container.addEventListener('touchend', this.dragEnd.bind(this));
      this.container.addEventListener('touchmove', this.position.bind(this));
      this.container.addEventListener('touchstart', this.click.bind(this));

      /* Context Menu */
      this.container.addEventListener('contextmenu', this.contextmenu.bind(this));
      /* Delete */
      this.container.addEventListener('keydown', this.key.bind(this));

      /* Zoom Mouse */
      this.container.addEventListener('wheel', this.zoom_enter.bind(this));
      /* Update data Nodes */
      this.container.addEventListener('input', this.updateNodeValue.bind(this));
      this.container.addEventListener('dblclick', this.dblclick.bind(this));
      /* Mobile zoom */
      this.container.onpointerdown = this.pointerdown_handler.bind(this);
      this.container.onpointermove = this.pointermove_handler.bind(this);
      this.container.onpointerup = this.pointerup_handler.bind(this);
      this.container.onpointercancel = this.pointerup_handler.bind(this);
      this.container.onpointerout = this.pointerup_handler.bind(this);
      this.container.onpointerleave = this.pointerup_handler.bind(this);
      this.load();
    }

    /* Mobile zoom */
  }, {
    key: "pointerdown_handler",
    value: function pointerdown_handler(ev) {
      this.evCache.push(ev);
    }
  }, {
    key: "pointermove_handler",
    value: function pointermove_handler(ev) {
      for (var i = 0; i < this.evCache.length; i++) {
        if (ev.pointerId == this.evCache[i].pointerId) {
          this.evCache[i] = ev;
          break;
        }
      }
      if (this.evCache.length == 2) {
        // Calculate the distance between the two pointers
        var curDiff = Math.abs(this.evCache[0].clientX - this.evCache[1].clientX);
        if (this.prevDiff > 100) {
          if (curDiff > this.prevDiff) {
            // The distance between the two pointers has increased

            this.zoom_in();
          }
          if (curDiff < this.prevDiff) {
            // The distance between the two pointers has decreased
            this.zoom_out();
          }
        }
        this.prevDiff = curDiff;
      }
    }
  }, {
    key: "pointerup_handler",
    value: function pointerup_handler(ev) {
      this.remove_event(ev);
      if (this.evCache.length < 2) {
        this.prevDiff = -1;
      }
    }
  }, {
    key: "remove_event",
    value: function remove_event(ev) {
      // Remove this event from the target's cache
      for (var i = 0; i < this.evCache.length; i++) {
        if (this.evCache[i].pointerId == ev.pointerId) {
          this.evCache.splice(i, 1);
          break;
        }
      }
    }
    /* End Mobile Zoom */
  }, {
    key: "load",
    value: function load() {
      for (var key in this.drawflow.drawflow[this.module].data) {
        this.addNodeImport(this.drawflow.drawflow[this.module].data[key], this.precanvas);
      }
      if (this.reroute) {
        for (var key in this.drawflow.drawflow[this.module].data) {
          this.addRerouteImport(this.drawflow.drawflow[this.module].data[key]);
        }
      }
      for (var key in this.drawflow.drawflow[this.module].data) {
        this.updateConnectionNodes('node-' + key);
      }
      var editor = this.drawflow.drawflow;
      var number = 1;
      Object.keys(editor).map(function (moduleName, index) {
        Object.keys(editor[moduleName].data).map(function (id, index2) {
          if (parseInt(id) >= number) {
            number = parseInt(id) + 1;
          }
        });
      });
      this.nodeId = number;
    }
  }, {
    key: "removeReouteConnectionSelected",
    value: function removeReouteConnectionSelected() {
      this.dispatch('connectionUnselected', true);
      if (this.reroute_fix_curvature) {
        this.connection_selected.parentElement.querySelectorAll('.main-path').forEach(function (item, i) {
          item.classList.remove('selected');
        });
      }
    }
  }, {
    key: "selectNode",
    value: function selectNode(id) {
      this.node_selected = document.getElementById('node-' + id);
      this.node_selected.classList.add('selected');
      this.dispatch('nodeSelected', id);
    }
  }, {
    key: "click",
    value: function click(e) {
      var _this$ele_selected, _this$ele_selected2;
      this.dispatch('click', e);
      if (this.editor_mode === 'fixed') {
        //return false;
        e.preventDefault();
        if (e.target.classList[0] === 'parent-drawflow' || e.target.classList[0] === 'drawflow') {
          this.ele_selected = e.target.closest('.parent-drawflow');
        } else {
          return false;
        }
      } else if (this.editor_mode === 'view') {
        if (e.target.closest('.drawflow') != null || e.target.matches('.parent-drawflow')) {
          this.ele_selected = e.target.closest('.parent-drawflow');
          e.preventDefault();
        }
      } else {
        this.first_click = e.target;
        this.ele_selected = e.target;
        if (e.button === 0) {
          this.contextmenuDel();
        }
        if (e.target.closest('.drawflow_content_node') != null) {
          this.ele_selected = e.target.closest('.drawflow_content_node').parentElement;
        }
      }
      switch ((_this$ele_selected = this.ele_selected) === null || _this$ele_selected === void 0 ? void 0 : _this$ele_selected.classList[0]) {
        case 'drawflow-node':
          if (this.node_selected != null) {
            this.node_selected.classList.remove('selected');
            if (this.node_selected != this.ele_selected) {
              this.dispatch('nodeUnselected', true);
            }
          }
          if (this.connection_selected != null) {
            this.connection_selected.classList.remove('selected');
            this.removeReouteConnectionSelected();
            this.connection_selected = null;
          }
          if (this.node_selected != this.ele_selected) {
            this.dispatch('nodeSelected', this.ele_selected.id.slice(5));
          }
          this.node_selected = this.ele_selected;
          this.node_selected.classList.add('selected');
          if (!this.draggable_inputs) {
            if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA' && e.target.tagName !== 'SELECT' && e.target.hasAttribute('contenteditable') !== true) {
              this.drag = true;
            }
          } else {
            if (e.target.tagName !== 'SELECT') {
              this.drag = true;
            }
          }
          break;
        case 'output':
          this.connection = true;
          if (this.node_selected != null) {
            this.node_selected.classList.remove('selected');
            this.node_selected = null;
            this.dispatch('nodeUnselected', true);
          }
          if (this.connection_selected != null) {
            this.connection_selected.classList.remove('selected');
            this.removeReouteConnectionSelected();
            this.connection_selected = null;
          }
          this.drawConnection(e.target);
          break;
        case 'parent-drawflow':
          if (this.node_selected != null) {
            this.node_selected.classList.remove('selected');
            this.node_selected = null;
            this.dispatch('nodeUnselected', true);
          }
          if (this.connection_selected != null) {
            this.connection_selected.classList.remove('selected');
            this.removeReouteConnectionSelected();
            this.connection_selected = null;
          }
          this.editor_selected = true;
          break;
        case 'drawflow':
          if (this.node_selected != null) {
            this.node_selected.classList.remove('selected');
            this.node_selected = null;
            this.dispatch('nodeUnselected', true);
          }
          if (this.connection_selected != null) {
            this.connection_selected.classList.remove('selected');
            this.removeReouteConnectionSelected();
            this.connection_selected = null;
          }
          this.editor_selected = true;
          break;
        case 'main-path':
          if (this.node_selected != null) {
            this.node_selected.classList.remove('selected');
            this.node_selected = null;
            this.dispatch('nodeUnselected', true);
          }
          if (this.connection_selected != null) {
            this.connection_selected.classList.remove('selected');
            this.removeReouteConnectionSelected();
            this.connection_selected = null;
          }
          this.connection_selected = this.ele_selected;
          this.connection_selected.classList.add('selected');
          var listclassConnection = this.connection_selected.parentElement.classList;
          if (listclassConnection.length > 1) {
            this.dispatch('connectionSelected', {
              output_id: listclassConnection[2].slice(14),
              input_id: listclassConnection[1].slice(13),
              output_class: listclassConnection[3],
              input_class: listclassConnection[4]
            });
            if (this.reroute_fix_curvature) {
              this.connection_selected.parentElement.querySelectorAll('.main-path').forEach(function (item, i) {
                item.classList.add('selected');
              });
            }
          }
          break;
        case 'point':
          this.drag_point = true;
          this.ele_selected.classList.add('selected');
          break;
        case 'drawflow-delete':
          if (this.node_selected) {
            this.removeNodeId(this.node_selected.id);
          }
          if (this.connection_selected) {
            this.removeConnection();
          }
          if (this.node_selected != null) {
            this.node_selected.classList.remove('selected');
            this.node_selected = null;
            this.dispatch('nodeUnselected', true);
          }
          if (this.connection_selected != null) {
            this.connection_selected.classList.remove('selected');
            this.removeReouteConnectionSelected();
            this.connection_selected = null;
          }
          break;
        default:
      }
      if (e.type === 'touchstart') {
        this.pos_x = e.touches[0].clientX;
        this.pos_x_start = e.touches[0].clientX;
        this.pos_y = e.touches[0].clientY;
        this.pos_y_start = e.touches[0].clientY;
        this.mouse_x = e.touches[0].clientX;
        this.mouse_y = e.touches[0].clientY;
      } else {
        this.pos_x = e.clientX;
        this.pos_x_start = e.clientX;
        this.pos_y = e.clientY;
        this.pos_y_start = e.clientY;
      }
      if (['input', 'output', 'main-path'].includes((_this$ele_selected2 = this.ele_selected) === null || _this$ele_selected2 === void 0 ? void 0 : _this$ele_selected2.classList[0])) {
        e.preventDefault();
      }
      this.dispatch('clickEnd', e);
    }
  }, {
    key: "position",
    value: function position(e) {
      if (e.type === 'touchmove') {
        var e_pos_x = e.touches[0].clientX;
        var e_pos_y = e.touches[0].clientY;
      } else {
        var e_pos_x = e.clientX;
        var e_pos_y = e.clientY;
      }
      if (this.connection) {
        this.updateConnection(e_pos_x, e_pos_y);
      }
      if (this.editor_selected) {
        x = this.canvas_x + -(this.pos_x - e_pos_x);
        y = this.canvas_y + -(this.pos_y - e_pos_y);
        this.dispatch('translate', {
          x: x,
          y: y
        });
        this.precanvas.style.transform = 'translate(' + x + 'px, ' + y + 'px) scale(' + this.zoom + ')';
      }
      if (this.drag) {
        e.preventDefault();
        var x = (this.pos_x - e_pos_x) * this.precanvas.clientWidth / (this.precanvas.clientWidth * this.zoom);
        var y = (this.pos_y - e_pos_y) * this.precanvas.clientHeight / (this.precanvas.clientHeight * this.zoom);
        this.pos_x = e_pos_x;
        this.pos_y = e_pos_y;
        this.ele_selected.style.top = this.ele_selected.offsetTop - y + 'px';
        this.ele_selected.style.left = this.ele_selected.offsetLeft - x + 'px';
        this.drawflow.drawflow[this.module].data[this.ele_selected.id.slice(5)].pos_x = this.ele_selected.offsetLeft - x;
        this.drawflow.drawflow[this.module].data[this.ele_selected.id.slice(5)].pos_y = this.ele_selected.offsetTop - y;
        this.updateConnectionNodes(this.ele_selected.id);
      }
      if (this.drag_point) {
        var x = (this.pos_x - e_pos_x) * this.precanvas.clientWidth / (this.precanvas.clientWidth * this.zoom);
        var y = (this.pos_y - e_pos_y) * this.precanvas.clientHeight / (this.precanvas.clientHeight * this.zoom);
        this.pos_x = e_pos_x;
        this.pos_y = e_pos_y;
        var pos_x = this.pos_x * (this.precanvas.clientWidth / (this.precanvas.clientWidth * this.zoom)) - this.precanvas.getBoundingClientRect().x * (this.precanvas.clientWidth / (this.precanvas.clientWidth * this.zoom));
        var pos_y = this.pos_y * (this.precanvas.clientHeight / (this.precanvas.clientHeight * this.zoom)) - this.precanvas.getBoundingClientRect().y * (this.precanvas.clientHeight / (this.precanvas.clientHeight * this.zoom));
        this.ele_selected.setAttributeNS(null, 'cx', pos_x);
        this.ele_selected.setAttributeNS(null, 'cy', pos_y);
        var nodeUpdate = this.ele_selected.parentElement.classList[2].slice(9);
        var nodeUpdateIn = this.ele_selected.parentElement.classList[1].slice(13);
        var output_class = this.ele_selected.parentElement.classList[3];
        var input_class = this.ele_selected.parentElement.classList[4];
        var numberPointPosition = Array.from(this.ele_selected.parentElement.children).indexOf(this.ele_selected) - 1;
        if (this.reroute_fix_curvature) {
          var numberMainPath = this.ele_selected.parentElement.querySelectorAll('.main-path').length - 1;
          numberPointPosition -= numberMainPath;
          if (numberPointPosition < 0) {
            numberPointPosition = 0;
          }
        }
        var nodeId = nodeUpdate.slice(5);
        var searchConnection = this.drawflow.drawflow[this.module].data[nodeId].outputs[output_class].connections.findIndex(function (item, i) {
          return item.node === nodeUpdateIn && item.output === input_class;
        });
        this.drawflow.drawflow[this.module].data[nodeId].outputs[output_class].connections[searchConnection].points[numberPointPosition] = {
          pos_x: pos_x,
          pos_y: pos_y
        };
        var parentSelected = this.ele_selected.parentElement.classList[2].slice(9);
        this.updateConnectionNodes(parentSelected);
      }
      if (e.type === 'touchmove') {
        this.mouse_x = e_pos_x;
        this.mouse_y = e_pos_y;
      }
      this.dispatch('mouseMove', {
        x: e_pos_x,
        y: e_pos_y
      });
    }
  }, {
    key: "dragEnd",
    value: function dragEnd(e) {
      if (e.type === 'touchend') {
        var e_pos_x = this.mouse_x;
        var e_pos_y = this.mouse_y;
        var ele_last = document.elementFromPoint(e_pos_x, e_pos_y);
      } else {
        var e_pos_x = e.clientX;
        var e_pos_y = e.clientY;
        var ele_last = e.target;
      }
      if (this.drag) {
        if (this.pos_x_start != e_pos_x || this.pos_y_start != e_pos_y) {
          this.dispatch('nodeMoved', this.ele_selected.id.slice(5));
        }
      }
      if (this.drag_point) {
        this.ele_selected.classList.remove('selected');
        if (this.pos_x_start != e_pos_x || this.pos_y_start != e_pos_y) {
          this.dispatch('rerouteMoved', this.ele_selected.parentElement.classList[2].slice(14));
        }
      }
      if (this.editor_selected) {
        this.canvas_x = this.canvas_x + -(this.pos_x - e_pos_x);
        this.canvas_y = this.canvas_y + -(this.pos_y - e_pos_y);
        this.editor_selected = false;
      }
      if (this.connection === true) {
        if (ele_last.classList[0] === 'input' || this.force_first_input && (ele_last.closest('.drawflow_content_node') != null || ele_last.classList[0] === 'drawflow-node')) {
          if (this.force_first_input && (ele_last.closest('.drawflow_content_node') != null || ele_last.classList[0] === 'drawflow-node')) {
            if (ele_last.closest('.drawflow_content_node') != null) {
              var input_id = ele_last.closest('.drawflow_content_node').parentElement.id;
            } else {
              var input_id = ele_last.id;
            }
            if (Object.keys(this.getNodeFromId(input_id.slice(5)).inputs).length === 0) {
              var input_class = false;
            } else {
              var input_class = 'input_1';
            }
          } else {
            // Fix connection;
            var input_id = ele_last.parentElement.parentElement.id;
            var input_class = ele_last.classList[1];
          }
          var output_id = this.ele_selected.parentElement.parentElement.id;
          var output_class = this.ele_selected.classList[1];
          if (output_id !== input_id && input_class !== false) {
            if (this.container.querySelectorAll('.connection.node_in_' + input_id + '.node_out_' + output_id + '.' + output_class + '.' + input_class).length === 0) {
              // Conection no exist save connection

              this.connection_ele.classList.add('node_in_' + input_id);
              this.connection_ele.classList.add('node_out_' + output_id);
              this.connection_ele.classList.add(output_class);
              this.connection_ele.classList.add(input_class);
              var id_input = input_id.slice(5);
              var id_output = output_id.slice(5);
              this.drawflow.drawflow[this.module].data[id_output].outputs[output_class].connections.push({
                node: id_input,
                output: input_class
              });
              this.drawflow.drawflow[this.module].data[id_input].inputs[input_class].connections.push({
                node: id_output,
                input: output_class
              });
              this.updateConnectionNodes('node-' + id_output);
              this.updateConnectionNodes('node-' + id_input);
              this.dispatch('connectionCreated', {
                output_id: id_output,
                input_id: id_input,
                output_class: output_class,
                input_class: input_class
              });
            } else {
              this.dispatch('connectionCancel', true);
              this.connection_ele.remove();
            }
            this.connection_ele = null;
          } else {
            // Connection exists Remove Connection;
            this.dispatch('connectionCancel', true);
            this.connection_ele.remove();
            this.connection_ele = null;
          }
        } else {
          // Remove Connection;
          this.dispatch('connectionCancel', true);
          this.connection_ele.remove();
          this.connection_ele = null;
        }
      }
      this.drag = false;
      this.drag_point = false;
      this.connection = false;
      this.ele_selected = null;
      this.editor_selected = false;
      this.dispatch('mouseUp', e);
    }
  }, {
    key: "contextmenu",
    value: function contextmenu(e) {
      this.dispatch('contextmenu', e);
      e.preventDefault();
      if (this.editor_mode === 'fixed' || this.editor_mode === 'view') {
        return false;
      }
      if (this.precanvas.getElementsByClassName('drawflow-delete').length) {
        this.precanvas.getElementsByClassName('drawflow-delete')[0].remove();
      }
      if (this.node_selected || this.connection_selected) {
        var deletebox = document.createElement('div');
        deletebox.classList.add('drawflow-delete');
        deletebox.innerHTML = 'x';
        if (this.node_selected) {
          this.node_selected.appendChild(deletebox);
        }
        if (this.connection_selected && this.connection_selected.parentElement.classList.length > 1) {
          deletebox.style.top = e.clientY * (this.precanvas.clientHeight / (this.precanvas.clientHeight * this.zoom)) - this.precanvas.getBoundingClientRect().y * (this.precanvas.clientHeight / (this.precanvas.clientHeight * this.zoom)) + 'px';
          deletebox.style.left = e.clientX * (this.precanvas.clientWidth / (this.precanvas.clientWidth * this.zoom)) - this.precanvas.getBoundingClientRect().x * (this.precanvas.clientWidth / (this.precanvas.clientWidth * this.zoom)) + 'px';
          this.precanvas.appendChild(deletebox);
        }
      }
    }
  }, {
    key: "contextmenuDel",
    value: function contextmenuDel() {
      if (this.precanvas.getElementsByClassName('drawflow-delete').length) {
        this.precanvas.getElementsByClassName('drawflow-delete')[0].remove();
      }
    }
  }, {
    key: "key",
    value: function key(e) {
      this.dispatch('keydown', e);
      if (this.editor_mode === 'fixed' || this.editor_mode === 'view') {
        return false;
      }
      if (e.key === 'Delete' || e.key === 'Backspace' && e.metaKey) {
        if (this.node_selected != null) {
          if (this.first_click.tagName !== 'INPUT' && this.first_click.tagName !== 'TEXTAREA' && this.first_click.hasAttribute('contenteditable') !== true) {
            this.removeNodeId(this.node_selected.id);
          }
        }
        if (this.connection_selected != null) {
          this.removeConnection();
        }
      }
    }
  }, {
    key: "zoom_enter",
    value: function zoom_enter(event, delta) {
      if (event.ctrlKey) {
        event.preventDefault();
        if (event.deltaY > 0) {
          // Zoom Out
          this.zoom_out();
        } else {
          // Zoom In
          this.zoom_in();
        }
      }
    }
  }, {
    key: "zoom_refresh",
    value: function zoom_refresh() {
      this.dispatch('zoom', this.zoom);
      this.canvas_x = this.canvas_x / this.zoom_last_value * this.zoom;
      this.canvas_y = this.canvas_y / this.zoom_last_value * this.zoom;
      this.zoom_last_value = this.zoom;
      this.precanvas.style.transform = 'translate(' + this.canvas_x + 'px, ' + this.canvas_y + 'px) scale(' + this.zoom + ')';
    }
  }, {
    key: "zoom_in",
    value: function zoom_in() {
      if (this.zoom < this.zoom_max) {
        this.zoom += this.zoom_value;
        this.zoom_refresh();
      }
    }
  }, {
    key: "zoom_out",
    value: function zoom_out() {
      if (this.zoom > this.zoom_min) {
        this.zoom -= this.zoom_value;
        this.zoom_refresh();
      }
    }
  }, {
    key: "zoom_reset",
    value: function zoom_reset() {
      if (this.zoom != 1) {
        this.zoom = 1;
        this.zoom_refresh();
      }
    }
  }, {
    key: "createCurvature",
    value: function createCurvature(start_pos_x, start_pos_y, end_pos_x, end_pos_y, curvature_value, type) {
      var line_x = start_pos_x;
      var line_y = start_pos_y;
      var x = end_pos_x;
      var y = end_pos_y;
      var curvature = curvature_value;
      //type openclose open close other
      switch (type) {
        case 'open':
          if (start_pos_x >= end_pos_x) {
            var hx1 = line_x + Math.abs(x - line_x) * curvature;
            var hx2 = x - Math.abs(x - line_x) * (curvature * -1);
          } else {
            var hx1 = line_x + Math.abs(x - line_x) * curvature;
            var hx2 = x - Math.abs(x - line_x) * curvature;
          }
          return ' M ' + line_x + ' ' + line_y + ' C ' + hx1 + ' ' + line_y + ' ' + hx2 + ' ' + y + ' ' + x + '  ' + y;
          break;
        case 'close':
          if (start_pos_x >= end_pos_x) {
            var hx1 = line_x + Math.abs(x - line_x) * (curvature * -1);
            var hx2 = x - Math.abs(x - line_x) * curvature;
          } else {
            var hx1 = line_x + Math.abs(x - line_x) * curvature;
            var hx2 = x - Math.abs(x - line_x) * curvature;
          }
          return ' M ' + line_x + ' ' + line_y + ' C ' + hx1 + ' ' + line_y + ' ' + hx2 + ' ' + y + ' ' + x + '  ' + y;
          break;
        case 'other':
          if (start_pos_x >= end_pos_x) {
            var hx1 = line_x + Math.abs(x - line_x) * (curvature * -1);
            var hx2 = x - Math.abs(x - line_x) * (curvature * -1);
          } else {
            var hx1 = line_x + Math.abs(x - line_x) * curvature;
            var hx2 = x - Math.abs(x - line_x) * curvature;
          }
          return ' M ' + line_x + ' ' + line_y + ' C ' + hx1 + ' ' + line_y + ' ' + hx2 + ' ' + y + ' ' + x + '  ' + y;
          break;
        default:
          var hx1 = line_x + Math.abs(x - line_x) * curvature;
          var hx2 = x - Math.abs(x - line_x) * curvature;
          return ' M ' + line_x + ' ' + line_y + ' C ' + hx1 + ' ' + line_y + ' ' + hx2 + ' ' + y + ' ' + x + '  ' + y;
      }
    }
  }, {
    key: "drawConnection",
    value: function drawConnection(ele) {
      var connection = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
      this.connection_ele = connection;
      var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
      path.classList.add('main-path');
      path.setAttributeNS(null, 'd', '');
      // path.innerHTML = 'a';
      connection.classList.add('connection');
      connection.appendChild(path);
      this.precanvas.appendChild(connection);
      var id_output = ele.parentElement.parentElement.id.slice(5);
      var output_class = ele.classList[1];
      this.dispatch('connectionStart', {
        output_id: id_output,
        output_class: output_class
      });
    }
  }, {
    key: "updateConnection",
    value: function updateConnection(eX, eY) {
      var precanvas = this.precanvas;
      var zoom = this.zoom;
      var precanvasWitdhZoom = precanvas.clientWidth / (precanvas.clientWidth * zoom);
      precanvasWitdhZoom = precanvasWitdhZoom || 0;
      var precanvasHeightZoom = precanvas.clientHeight / (precanvas.clientHeight * zoom);
      precanvasHeightZoom = precanvasHeightZoom || 0;
      var path = this.connection_ele.children[0];
      var line_x = this.ele_selected.offsetWidth / 2 + (this.ele_selected.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
      var line_y = this.ele_selected.offsetHeight / 2 + (this.ele_selected.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
      var x = eX * (this.precanvas.clientWidth / (this.precanvas.clientWidth * this.zoom)) - this.precanvas.getBoundingClientRect().x * (this.precanvas.clientWidth / (this.precanvas.clientWidth * this.zoom));
      var y = eY * (this.precanvas.clientHeight / (this.precanvas.clientHeight * this.zoom)) - this.precanvas.getBoundingClientRect().y * (this.precanvas.clientHeight / (this.precanvas.clientHeight * this.zoom));
      var curvature = this.curvature;
      var lineCurve = this.createCurvature(line_x, line_y, x, y, curvature, 'openclose');
      path.setAttributeNS(null, 'd', lineCurve);
    }
  }, {
    key: "addConnection",
    value: function addConnection(id_output, id_input, output_class, input_class) {
      var nodeOneModule = this.getModuleFromNodeId(id_output);
      var nodeTwoModule = this.getModuleFromNodeId(id_input);
      if (nodeOneModule === nodeTwoModule) {
        var dataNode = this.getNodeFromId(id_output);
        var exist = false;
        for (var checkOutput in dataNode.outputs[output_class].connections) {
          var connectionSearch = dataNode.outputs[output_class].connections[checkOutput];
          if (connectionSearch.node == id_input && connectionSearch.output == input_class) {
            exist = true;
          }
        }
        // Check connection exist
        if (exist === false) {
          //Create Connection
          this.drawflow.drawflow[nodeOneModule].data[id_output].outputs[output_class].connections.push({
            node: id_input.toString(),
            output: input_class
          });
          this.drawflow.drawflow[nodeOneModule].data[id_input].inputs[input_class].connections.push({
            node: id_output.toString(),
            input: output_class
          });
          if (this.module === nodeOneModule) {
            //Draw connection
            var connection = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            path.classList.add('main-path');
            path.setAttributeNS(null, 'd', '');
            // path.innerHTML = 'a';
            connection.classList.add('connection');
            connection.classList.add('node_in_node-' + id_input);
            connection.classList.add('node_out_node-' + id_output);
            connection.classList.add(output_class);
            connection.classList.add(input_class);
            connection.appendChild(path);
            this.precanvas.appendChild(connection);
            this.updateConnectionNodes('node-' + id_output);
            this.updateConnectionNodes('node-' + id_input);
          }
          this.dispatch('connectionCreated', {
            output_id: id_output,
            input_id: id_input,
            output_class: output_class,
            input_class: input_class
          });
        }
      }
    }
  }, {
    key: "updateConnectionNodes",
    value: function updateConnectionNodes(id) {
      // Aquí nos quedamos;
      var idSearch = 'node_in_' + id;
      var idSearchOut = 'node_out_' + id;
      var line_path = this.line_path / 2;
      var container = this.container;
      var precanvas = this.precanvas;
      var curvature = this.curvature;
      var createCurvature = this.createCurvature;
      var reroute_curvature = this.reroute_curvature;
      var reroute_curvature_start_end = this.reroute_curvature_start_end;
      var reroute_fix_curvature = this.reroute_fix_curvature;
      var rerouteWidth = this.reroute_width;
      var zoom = this.zoom;
      var precanvasWitdhZoom = precanvas.clientWidth / (precanvas.clientWidth * zoom);
      precanvasWitdhZoom = precanvasWitdhZoom || 0;
      var precanvasHeightZoom = precanvas.clientHeight / (precanvas.clientHeight * zoom);
      precanvasHeightZoom = precanvasHeightZoom || 0;
      var elemsOut = container.querySelectorAll(".".concat(idSearchOut));
      Object.keys(elemsOut).map(function (item, index) {
        if (elemsOut[item].querySelector('.point') === null) {
          var elemtsearchId_out = container.querySelector("#".concat(id));
          var id_search = elemsOut[item].classList[1].replace('node_in_', '');
          var elemtsearchId = container.querySelector("#".concat(id_search));
          var elemtsearch = elemtsearchId.querySelectorAll('.' + elemsOut[item].classList[4])[0];
          var eX = elemtsearch.offsetWidth / 2 + (elemtsearch.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
          var eY = elemtsearch.offsetHeight / 2 + (elemtsearch.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
          var elemtsearchOut = elemtsearchId_out.querySelectorAll('.' + elemsOut[item].classList[3])[0];
          var line_x = elemtsearchOut.offsetWidth / 2 + (elemtsearchOut.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
          var line_y = elemtsearchOut.offsetHeight / 2 + (elemtsearchOut.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
          var x = eX;
          var y = eY;
          var lineCurve = createCurvature(line_x, line_y, x, y, curvature, 'openclose');
          elemsOut[item].children[0].setAttributeNS(null, 'd', lineCurve);
        } else {
          var points = elemsOut[item].querySelectorAll('.point');
          var linecurve = '';
          var reoute_fix = [];
          points.forEach(function (item, i) {
            if (i === 0 && points.length - 1 === 0) {
              var elemtsearchId_out = container.querySelector("#".concat(id));
              var elemtsearch = item;
              var eX = (elemtsearch.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var eY = (elemtsearch.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var elemtsearchOut = elemtsearchId_out.querySelectorAll('.' + item.parentElement.classList[3])[0];
              var line_x = elemtsearchOut.offsetWidth / 2 + (elemtsearchOut.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
              var line_y = elemtsearchOut.offsetHeight / 2 + (elemtsearchOut.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
              var x = eX;
              var y = eY;
              var lineCurveSearch = createCurvature(line_x, line_y, x, y, reroute_curvature_start_end, 'open');
              linecurve += lineCurveSearch;
              reoute_fix.push(lineCurveSearch);
              var elemtsearchId_out = item;
              var id_search = item.parentElement.classList[1].replace('node_in_', '');
              var elemtsearchId = container.querySelector("#".concat(id_search));
              var elemtsearch = elemtsearchId.querySelectorAll('.' + item.parentElement.classList[4])[0];
              var elemtsearchIn = elemtsearchId.querySelectorAll('.' + item.parentElement.classList[4])[0];
              var eX = elemtsearchIn.offsetWidth / 2 + (elemtsearchIn.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
              var eY = elemtsearchIn.offsetHeight / 2 + (elemtsearchIn.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
              var line_x = (elemtsearchId_out.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var line_y = (elemtsearchId_out.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var x = eX;
              var y = eY;
              var lineCurveSearch = createCurvature(line_x, line_y, x, y, reroute_curvature_start_end, 'close');
              linecurve += lineCurveSearch;
              reoute_fix.push(lineCurveSearch);
            } else if (i === 0) {
              var elemtsearchId_out = container.querySelector("#".concat(id));
              var elemtsearch = item;
              var eX = (elemtsearch.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var eY = (elemtsearch.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var elemtsearchOut = elemtsearchId_out.querySelectorAll('.' + item.parentElement.classList[3])[0];
              var line_x = elemtsearchOut.offsetWidth / 2 + (elemtsearchOut.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
              var line_y = elemtsearchOut.offsetHeight / 2 + (elemtsearchOut.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
              var x = eX;
              var y = eY;
              var lineCurveSearch = createCurvature(line_x, line_y, x, y, reroute_curvature_start_end, 'open');
              linecurve += lineCurveSearch;
              reoute_fix.push(lineCurveSearch);

              // SECOND
              var elemtsearchId_out = item;
              var elemtsearch = points[i + 1];
              var eX = (elemtsearch.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var eY = (elemtsearch.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var line_x = (elemtsearchId_out.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var line_y = (elemtsearchId_out.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var x = eX;
              var y = eY;
              var lineCurveSearch = createCurvature(line_x, line_y, x, y, reroute_curvature, 'other');
              linecurve += lineCurveSearch;
              reoute_fix.push(lineCurveSearch);
            } else if (i === points.length - 1) {
              var elemtsearchId_out = item;
              var id_search = item.parentElement.classList[1].replace('node_in_', '');
              var elemtsearchId = container.querySelector("#".concat(id_search));
              var elemtsearch = elemtsearchId.querySelectorAll('.' + item.parentElement.classList[4])[0];
              var elemtsearchIn = elemtsearchId.querySelectorAll('.' + item.parentElement.classList[4])[0];
              var eX = elemtsearchIn.offsetWidth / 2 + (elemtsearchIn.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
              var eY = elemtsearchIn.offsetHeight / 2 + (elemtsearchIn.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
              var line_x = (elemtsearchId_out.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * (precanvas.clientWidth / (precanvas.clientWidth * zoom)) + rerouteWidth;
              var line_y = (elemtsearchId_out.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * (precanvas.clientHeight / (precanvas.clientHeight * zoom)) + rerouteWidth;
              var x = eX;
              var y = eY;
              var lineCurveSearch = createCurvature(line_x, line_y, x, y, reroute_curvature_start_end, 'close');
              linecurve += lineCurveSearch;
              reoute_fix.push(lineCurveSearch);
            } else {
              var elemtsearchId_out = item;
              var elemtsearch = points[i + 1];
              var eX = (elemtsearch.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * (precanvas.clientWidth / (precanvas.clientWidth * zoom)) + rerouteWidth;
              var eY = (elemtsearch.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * (precanvas.clientHeight / (precanvas.clientHeight * zoom)) + rerouteWidth;
              var line_x = (elemtsearchId_out.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * (precanvas.clientWidth / (precanvas.clientWidth * zoom)) + rerouteWidth;
              var line_y = (elemtsearchId_out.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * (precanvas.clientHeight / (precanvas.clientHeight * zoom)) + rerouteWidth;
              var x = eX;
              var y = eY;
              var lineCurveSearch = createCurvature(line_x, line_y, x, y, reroute_curvature, 'other');
              linecurve += lineCurveSearch;
              reoute_fix.push(lineCurveSearch);
            }
          });
          if (reroute_fix_curvature) {
            reoute_fix.forEach(function (itempath, i) {
              elemsOut[item].children[i].setAttributeNS(null, 'd', itempath);
            });
          } else {
            elemsOut[item].children[0].setAttributeNS(null, 'd', linecurve);
          }
        }
      });
      var elems = container.querySelectorAll(".".concat(idSearch));
      Object.keys(elems).map(function (item, index) {
        if (elems[item].querySelector('.point') === null) {
          var elemtsearchId_in = container.querySelector("#".concat(id));
          var id_search = elems[item].classList[2].replace('node_out_', '');
          var elemtsearchId = container.querySelector("#".concat(id_search));
          var elemtsearch = elemtsearchId.querySelectorAll('.' + elems[item].classList[3])[0];
          var line_x = elemtsearch.offsetWidth / 2 + (elemtsearch.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
          var line_y = elemtsearch.offsetHeight / 2 + (elemtsearch.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
          var elemtsearchId_in = elemtsearchId_in.querySelectorAll('.' + elems[item].classList[4])[0];
          var x = elemtsearchId_in.offsetWidth / 2 + (elemtsearchId_in.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
          var y = elemtsearchId_in.offsetHeight / 2 + (elemtsearchId_in.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
          var lineCurve = createCurvature(line_x, line_y, x, y, curvature, 'openclose');
          elems[item].children[0].setAttributeNS(null, 'd', lineCurve);
        } else {
          var points = elems[item].querySelectorAll('.point');
          var linecurve = '';
          var reoute_fix = [];
          points.forEach(function (item, i) {
            if (i === 0 && points.length - 1 === 0) {
              var elemtsearchId_out = container.querySelector("#".concat(id));
              var elemtsearch = item;
              var line_x = (elemtsearch.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var line_y = (elemtsearch.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var elemtsearchIn = elemtsearchId_out.querySelectorAll('.' + item.parentElement.classList[4])[0];
              var eX = elemtsearchIn.offsetWidth / 2 + (elemtsearchIn.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
              var eY = elemtsearchIn.offsetHeight / 2 + (elemtsearchIn.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
              var x = eX;
              var y = eY;
              var lineCurveSearch = createCurvature(line_x, line_y, x, y, reroute_curvature_start_end, 'close');
              linecurve += lineCurveSearch;
              reoute_fix.push(lineCurveSearch);
              var elemtsearchId_out = item;
              var id_search = item.parentElement.classList[2].replace('node_out_', '');
              var elemtsearchId = container.querySelector("#".concat(id_search));
              var elemtsearch = elemtsearchId.querySelectorAll('.' + item.parentElement.classList[3])[0];
              var elemtsearchOut = elemtsearchId.querySelectorAll('.' + item.parentElement.classList[3])[0];
              var line_x = elemtsearchOut.offsetWidth / 2 + (elemtsearchOut.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
              var line_y = elemtsearchOut.offsetHeight / 2 + (elemtsearchOut.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
              var eX = (elemtsearchId_out.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var eY = (elemtsearchId_out.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var x = eX;
              var y = eY;
              var lineCurveSearch = createCurvature(line_x, line_y, x, y, reroute_curvature_start_end, 'open');
              linecurve += lineCurveSearch;
              reoute_fix.push(lineCurveSearch);
            } else if (i === 0) {
              // FIRST
              var elemtsearchId_out = item;
              var id_search = item.parentElement.classList[2].replace('node_out_', '');
              var elemtsearchId = container.querySelector("#".concat(id_search));
              var elemtsearch = elemtsearchId.querySelectorAll('.' + item.parentElement.classList[3])[0];
              var elemtsearchOut = elemtsearchId.querySelectorAll('.' + item.parentElement.classList[3])[0];
              var line_x = elemtsearchOut.offsetWidth / 2 + (elemtsearchOut.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
              var line_y = elemtsearchOut.offsetHeight / 2 + (elemtsearchOut.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
              var eX = (elemtsearchId_out.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var eY = (elemtsearchId_out.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var x = eX;
              var y = eY;
              var lineCurveSearch = createCurvature(line_x, line_y, x, y, reroute_curvature_start_end, 'open');
              linecurve += lineCurveSearch;
              reoute_fix.push(lineCurveSearch);

              // SECOND
              var elemtsearchId_out = item;
              var elemtsearch = points[i + 1];
              var eX = (elemtsearch.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var eY = (elemtsearch.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var line_x = (elemtsearchId_out.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var line_y = (elemtsearchId_out.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var x = eX;
              var y = eY;
              var lineCurveSearch = createCurvature(line_x, line_y, x, y, reroute_curvature, 'other');
              linecurve += lineCurveSearch;
              reoute_fix.push(lineCurveSearch);
            } else if (i === points.length - 1) {
              var elemtsearchId_out = item;
              var id_search = item.parentElement.classList[1].replace('node_in_', '');
              var elemtsearchId = container.querySelector("#".concat(id_search));
              var elemtsearch = elemtsearchId.querySelectorAll('.' + item.parentElement.classList[4])[0];
              var elemtsearchIn = elemtsearchId.querySelectorAll('.' + item.parentElement.classList[4])[0];
              var eX = elemtsearchIn.offsetWidth / 2 + (elemtsearchIn.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom;
              var eY = elemtsearchIn.offsetHeight / 2 + (elemtsearchIn.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom;
              var line_x = (elemtsearchId_out.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var line_y = (elemtsearchId_out.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var x = eX;
              var y = eY;
              var lineCurveSearch = createCurvature(line_x, line_y, x, y, reroute_curvature_start_end, 'close');
              linecurve += lineCurveSearch;
              reoute_fix.push(lineCurveSearch);
            } else {
              var elemtsearchId_out = item;
              var elemtsearch = points[i + 1];
              var eX = (elemtsearch.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var eY = (elemtsearch.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var line_x = (elemtsearchId_out.getBoundingClientRect().x - precanvas.getBoundingClientRect().x) * precanvasWitdhZoom + rerouteWidth;
              var line_y = (elemtsearchId_out.getBoundingClientRect().y - precanvas.getBoundingClientRect().y) * precanvasHeightZoom + rerouteWidth;
              var x = eX;
              var y = eY;
              var lineCurveSearch = createCurvature(line_x, line_y, x, y, reroute_curvature, 'other');
              linecurve += lineCurveSearch;
              reoute_fix.push(lineCurveSearch);
            }
          });
          if (reroute_fix_curvature) {
            reoute_fix.forEach(function (itempath, i) {
              elems[item].children[i].setAttributeNS(null, 'd', itempath);
            });
          } else {
            elems[item].children[0].setAttributeNS(null, 'd', linecurve);
          }
        }
      });
    }
  }, {
    key: "dblclick",
    value: function dblclick(e) {
      if (this.connection_selected != null && this.reroute) {
        this.createReroutePoint(this.connection_selected);
      }
      if (e.target.classList[0] === 'point') {
        this.removeReroutePoint(e.target);
      }
    }
  }, {
    key: "createReroutePoint",
    value: function createReroutePoint(ele) {
      this.connection_selected.classList.remove('selected');
      var nodeUpdate = this.connection_selected.parentElement.classList[2].slice(9);
      var nodeUpdateIn = this.connection_selected.parentElement.classList[1].slice(13);
      var output_class = this.connection_selected.parentElement.classList[3];
      var input_class = this.connection_selected.parentElement.classList[4];
      this.connection_selected = null;
      var point = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
      point.classList.add('point');
      var pos_x = this.pos_x * (this.precanvas.clientWidth / (this.precanvas.clientWidth * this.zoom)) - this.precanvas.getBoundingClientRect().x * (this.precanvas.clientWidth / (this.precanvas.clientWidth * this.zoom));
      var pos_y = this.pos_y * (this.precanvas.clientHeight / (this.precanvas.clientHeight * this.zoom)) - this.precanvas.getBoundingClientRect().y * (this.precanvas.clientHeight / (this.precanvas.clientHeight * this.zoom));
      point.setAttributeNS(null, 'cx', pos_x);
      point.setAttributeNS(null, 'cy', pos_y);
      point.setAttributeNS(null, 'r', this.reroute_width);
      var position_add_array_point = 0;
      if (this.reroute_fix_curvature) {
        var numberPoints = ele.parentElement.querySelectorAll('.main-path').length;
        var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        path.classList.add('main-path');
        path.setAttributeNS(null, 'd', '');
        ele.parentElement.insertBefore(path, ele.parentElement.children[numberPoints]);
        if (numberPoints === 1) {
          ele.parentElement.appendChild(point);
        } else {
          var search_point = Array.from(ele.parentElement.children).indexOf(ele);
          position_add_array_point = search_point;
          ele.parentElement.insertBefore(point, ele.parentElement.children[search_point + numberPoints + 1]);
        }
      } else {
        ele.parentElement.appendChild(point);
      }
      var nodeId = nodeUpdate.slice(5);
      var searchConnection = this.drawflow.drawflow[this.module].data[nodeId].outputs[output_class].connections.findIndex(function (item, i) {
        return item.node === nodeUpdateIn && item.output === input_class;
      });
      if (this.drawflow.drawflow[this.module].data[nodeId].outputs[output_class].connections[searchConnection].points === undefined) {
        this.drawflow.drawflow[this.module].data[nodeId].outputs[output_class].connections[searchConnection].points = [];
      }
      if (this.reroute_fix_curvature) {
        if (position_add_array_point > 0 || this.drawflow.drawflow[this.module].data[nodeId].outputs[output_class].connections[searchConnection].points !== []) {
          this.drawflow.drawflow[this.module].data[nodeId].outputs[output_class].connections[searchConnection].points.splice(position_add_array_point, 0, {
            pos_x: pos_x,
            pos_y: pos_y
          });
        } else {
          this.drawflow.drawflow[this.module].data[nodeId].outputs[output_class].connections[searchConnection].points.push({
            pos_x: pos_x,
            pos_y: pos_y
          });
        }
        ele.parentElement.querySelectorAll('.main-path').forEach(function (item, i) {
          item.classList.remove('selected');
        });
      } else {
        this.drawflow.drawflow[this.module].data[nodeId].outputs[output_class].connections[searchConnection].points.push({
          pos_x: pos_x,
          pos_y: pos_y
        });
      }
      this.dispatch('addReroute', nodeId);
      this.updateConnectionNodes(nodeUpdate);
    }
  }, {
    key: "removeReroutePoint",
    value: function removeReroutePoint(ele) {
      var nodeUpdate = ele.parentElement.classList[2].slice(9);
      var nodeUpdateIn = ele.parentElement.classList[1].slice(13);
      var output_class = ele.parentElement.classList[3];
      var input_class = ele.parentElement.classList[4];
      var numberPointPosition = Array.from(ele.parentElement.children).indexOf(ele);
      var nodeId = nodeUpdate.slice(5);
      var searchConnection = this.drawflow.drawflow[this.module].data[nodeId].outputs[output_class].connections.findIndex(function (item, i) {
        return item.node === nodeUpdateIn && item.output === input_class;
      });
      if (this.reroute_fix_curvature) {
        var numberMainPath = ele.parentElement.querySelectorAll('.main-path').length;
        ele.parentElement.children[numberMainPath - 1].remove();
        numberPointPosition -= numberMainPath;
        if (numberPointPosition < 0) {
          numberPointPosition = 0;
        }
      } else {
        numberPointPosition--;
      }
      this.drawflow.drawflow[this.module].data[nodeId].outputs[output_class].connections[searchConnection].points.splice(numberPointPosition, 1);
      ele.remove();
      this.dispatch('removeReroute', nodeId);
      this.updateConnectionNodes(nodeUpdate);
    }
  }, {
    key: "registerNode",
    value: function registerNode(name, html) {
      var props = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;
      var options = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;
      this.noderegister[name] = {
        html: html,
        props: props,
        options: options
      };
    }
  }, {
    key: "getNodeFromId",
    value: function getNodeFromId(id) {
      var moduleName = this.getModuleFromNodeId(id);
      if (moduleName == '') {
        return {};
      }
      return JSON.parse(JSON.stringify(this.drawflow.drawflow[moduleName]['data'][id]));
    }
  }, {
    key: "getNodesFromName",
    value: function getNodesFromName(name) {
      var nodes = [];
      var editor = this.drawflow.drawflow;
      Object.keys(editor).map(function (moduleName, index) {
        for (var node in editor[moduleName].data) {
          if (editor[moduleName].data[node].name == name) {
            nodes.push(editor[moduleName].data[node].id);
          }
        }
      });
      return nodes;
    }
  }, {
    key: "addNode",
    value: function addNode(name, num_in, num_out, ele_pos_x, ele_pos_y, classoverride, data, html) {
      var _this = this;
      var typenode = arguments.length > 8 && arguments[8] !== undefined ? arguments[8] : false;
      if (this.useuuid) {
        var newNodeId = this.getUuid();
      } else {
        var newNodeId = this.nodeId;
      }
      var parent = document.createElement('div');
      parent.classList.add('parent-node');
      var node = document.createElement('div');
      node.innerHTML = '';
      node.setAttribute('id', 'node-' + newNodeId);
      node.classList.add('drawflow-node');
      if (classoverride != '') {
        var _node$classList;
        (_node$classList = node.classList).add.apply(_node$classList, _toConsumableArray(classoverride.split(' ')));
      }
      var inputs = document.createElement('div');
      inputs.classList.add('inputs');
      var outputs = document.createElement('div');
      outputs.classList.add('outputs');
      var uifm_options = document.createElement('div');
      uifm_options.classList.add('drawflow_uifm_opt_edit');
      uifm_options.innerHTML = "\n\t\t<i class=\"fa fa-pencil\" aria-hidden=\"true\"></i>\n\t\t";
      var uifm_options_delete = document.createElement('div');
      uifm_options_delete.classList.add('drawflow_uifm_opt_delete');
      uifm_options_delete.innerHTML = "\n\t\t <i class=\"fa fa-trash\" aria-hidden=\"true\"></i>\n\t\t";
      var json_inputs = {};
      for (var x = 0; x < num_in; x++) {
        var input = document.createElement('div');
        input.classList.add('input');
        input.classList.add('input_' + (x + 1));
        json_inputs['input_' + (x + 1)] = {
          connections: []
        };
        inputs.appendChild(input);
      }
      var json_outputs = {};
      for (var x = 0; x < num_out; x++) {
        var output = document.createElement('div');
        output.classList.add('output');
        output.classList.add('output_' + (x + 1));
        json_outputs['output_' + (x + 1)] = {
          connections: []
        };
        outputs.appendChild(output);
      }
      var content = document.createElement('div');
      content.classList.add('drawflow_content_node');
      if (typenode === false) {
        content.innerHTML = html;
      } else if (typenode === true) {
        content.appendChild(this.noderegister[html].html.cloneNode(true));
      } else {
        if (parseInt(this.render.version) === 3) {
          //Vue 3
          var wrapper = this.render.h(this.noderegister[html].html, this.noderegister[html].props, this.noderegister[html].options);
          wrapper.appContext = this.parent;
          this.render.render(wrapper, content);
        } else {
          // Vue 2
          var _wrapper = new this.render(_objectSpread({
            parent: this.parent,
            render: function render(h) {
              return h(_this.noderegister[html].html, {
                props: _this.noderegister[html].props
              });
            }
          }, this.noderegister[html].options)).$mount();
          //
          content.appendChild(_wrapper.$el);
        }
      }
      Object.entries(data).forEach(function (key, value) {
        if (_typeof(key[1]) === 'object') {
          insertObjectkeys(null, key[0], key[0]);
        } else {
          var elems = content.querySelectorAll('[df-' + key[0] + ']');
          for (var i = 0; i < elems.length; i++) {
            elems[i].value = key[1];
            if (elems[i].isContentEditable) {
              elems[i].innerText = key[1];
            }
          }
        }
      });
      function insertObjectkeys(object, name, completname) {
        if (object === null) {
          var object = data[name];
        } else {
          var object = object[name];
        }
        if (object !== null) {
          Object.entries(object).forEach(function (key, value) {
            if (_typeof(key[1]) === 'object') {
              insertObjectkeys(object, key[0], completname + '-' + key[0]);
            } else {
              var elems = content.querySelectorAll('[df-' + completname + '-' + key[0] + ']');
              for (var i = 0; i < elems.length; i++) {
                elems[i].value = key[1];
                if (elems[i].isContentEditable) {
                  elems[i].innerText = key[1];
                }
              }
            }
          });
        }
      }
      node.appendChild(inputs);
      node.appendChild(content);
      node.appendChild(outputs);
      node.appendChild(uifm_options);
      node.appendChild(uifm_options_delete);
      node.style.top = ele_pos_y + 'px';
      node.style.left = ele_pos_x + 'px';
      parent.appendChild(node);
      this.precanvas.appendChild(parent);
      var json = {
        id: newNodeId,
        name: name,
        data: data,
        class: classoverride,
        html: html,
        typenode: typenode,
        inputs: json_inputs,
        outputs: json_outputs,
        pos_x: ele_pos_x,
        pos_y: ele_pos_y
      };
      this.drawflow.drawflow[this.module].data[newNodeId] = json;
      this.dispatch('nodeCreated', newNodeId);
      if (!this.useuuid) {
        this.nodeId++;
      }
      node.addEventListener('mouseenter', function () {
        this.querySelector('.drawflow_uifm_opt_edit').style.display = 'block';
        this.querySelector('.drawflow_uifm_opt_delete').style.display = 'block';
      });
      node.addEventListener('mouseleave', function () {
        this.querySelector('.drawflow_uifm_opt_edit').style.display = 'none';
        this.querySelector('.drawflow_uifm_opt_delete').style.display = 'none';
      });
      var self = this;
      document.querySelector("#node-".concat(newNodeId, " .drawflow_uifm_opt_edit")).addEventListener('click', function () {
        document.querySelectorAll('.drawflow-node').forEach(function (node) {
          if (node.classList.contains('selected')) {
            // Remove the 'selected' class
            node.classList.remove('selected');
          }
        });
        self.selectNode(newNodeId);
        document.querySelector('.sfdc-nav-tabs a[href="#uiformc-menu-sec1"]').click();
      });
      document.querySelector("#node-".concat(newNodeId, " .drawflow_uifm_opt_delete")).addEventListener('click', function () {
        self.selectNode(newNodeId);
        if (self.node_selected) {
          self.dispatch('uifmNodeUnselected', data.id);
          self.removeNodeId(self.node_selected.id);
        }
        if (self.connection_selected) {
          self.removeConnection();
        }
        if (self.node_selected != null) {
          self.node_selected.classList.remove('selected');
          self.node_selected = null;
          self.dispatch('nodeUnselected', true);
        }
        if (self.connection_selected != null) {
          self.connection_selected.classList.remove('selected');
          self.removeReouteConnectionSelected();
          self.connection_selected = null;
        }
      });
      return newNodeId;
    }
  }, {
    key: "addNodeImport",
    value: function addNodeImport(dataNode, precanvas) {
      var _this2 = this;
      var parent = document.createElement('div');
      parent.classList.add('parent-node');
      var node = document.createElement('div');
      node.innerHTML = '';
      node.setAttribute('id', 'node-' + dataNode.id);
      node.classList.add('drawflow-node');
      if (dataNode.class != '') {
        var _node$classList2;
        (_node$classList2 = node.classList).add.apply(_node$classList2, _toConsumableArray(dataNode.class.split(' ')));
      }
      var inputs = document.createElement('div');
      inputs.classList.add('inputs');
      var outputs = document.createElement('div');
      outputs.classList.add('outputs');
      var uifm_options = document.createElement('div');
      uifm_options.classList.add('drawflow_uifm_opt_edit');
      uifm_options.innerHTML = "\n\t\t<i class=\"fa fa-pencil\" aria-hidden=\"true\"></i>\n\t\t";
      var uifm_options_delete = document.createElement('div');
      uifm_options_delete.classList.add('drawflow_uifm_opt_delete');
      uifm_options_delete.innerHTML = "\n\t\t <i class=\"fa fa-trash\" aria-hidden=\"true\"></i>\n\t\t";
      Object.keys(dataNode.inputs).map(function (input_item, index) {
        var input = document.createElement('div');
        input.classList.add('input');
        input.classList.add(input_item);
        inputs.appendChild(input);
        Object.keys(dataNode.inputs[input_item].connections).map(function (output_item, index) {
          var connection = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
          var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
          path.classList.add('main-path');
          path.setAttributeNS(null, 'd', '');
          // path.innerHTML = 'a';
          connection.classList.add('connection');
          connection.classList.add('node_in_node-' + dataNode.id);
          connection.classList.add('node_out_node-' + dataNode.inputs[input_item].connections[output_item].node);
          connection.classList.add(dataNode.inputs[input_item].connections[output_item].input);
          connection.classList.add(input_item);
          connection.appendChild(path);
          precanvas.appendChild(connection);
        });
      });
      for (var x = 0; x < Object.keys(dataNode.outputs).length; x++) {
        var output = document.createElement('div');
        output.classList.add('output');
        output.classList.add('output_' + (x + 1));
        outputs.appendChild(output);
      }
      var content = document.createElement('div');
      content.classList.add('drawflow_content_node');
      if (dataNode.typenode === false) {
        content.innerHTML = dataNode.html;
      } else if (dataNode.typenode === true) {
        content.appendChild(this.noderegister[dataNode.html].html.cloneNode(true));
      } else {
        if (parseInt(this.render.version) === 3) {
          //Vue 3
          var wrapper = this.render.h(this.noderegister[dataNode.html].html, this.noderegister[dataNode.html].props, this.noderegister[dataNode.html].options);
          wrapper.appContext = this.parent;
          this.render.render(wrapper, content);
        } else {
          //Vue 2
          var _wrapper2 = new this.render(_objectSpread({
            parent: this.parent,
            render: function render(h) {
              return h(_this2.noderegister[dataNode.html].html, {
                props: _this2.noderegister[dataNode.html].props
              });
            }
          }, this.noderegister[dataNode.html].options)).$mount();
          content.appendChild(_wrapper2.$el);
        }
      }
      Object.entries(dataNode.data).forEach(function (key, value) {
        if (_typeof(key[1]) === 'object') {
          insertObjectkeys(null, key[0], key[0]);
        } else {
          var elems = content.querySelectorAll('[df-' + key[0] + ']');
          for (var i = 0; i < elems.length; i++) {
            elems[i].value = key[1];
            if (elems[i].isContentEditable) {
              elems[i].innerText = key[1];
            }
          }
        }
      });
      function insertObjectkeys(object, name, completname) {
        if (object === null) {
          var object = dataNode.data[name];
        } else {
          var object = object[name];
        }
        if (object !== null) {
          Object.entries(object).forEach(function (key, value) {
            if (_typeof(key[1]) === 'object') {
              insertObjectkeys(object, key[0], completname + '-' + key[0]);
            } else {
              var elems = content.querySelectorAll('[df-' + completname + '-' + key[0] + ']');
              for (var i = 0; i < elems.length; i++) {
                elems[i].value = key[1];
                if (elems[i].isContentEditable) {
                  elems[i].innerText = key[1];
                }
              }
            }
          });
        }
      }
      node.appendChild(inputs);
      node.appendChild(content);
      node.appendChild(outputs);
      node.appendChild(uifm_options);
      node.appendChild(uifm_options_delete);
      node.style.top = dataNode.pos_y + 'px';
      node.style.left = dataNode.pos_x + 'px';
      parent.appendChild(node);
      this.precanvas.appendChild(parent);

      //edit icon
      node.addEventListener('mouseenter', function () {
        // Show the element with class 'drawflow_uifm_opt_edit' within this node
        this.querySelector('.drawflow_uifm_opt_edit').style.display = 'block';
        this.querySelector('.drawflow_uifm_opt_delete').style.display = 'block';
      });
      node.addEventListener('mouseleave', function () {
        // Hide the element with class 'drawflow_uifm_opt_edit' within this node
        this.querySelector('.drawflow_uifm_opt_edit').style.display = 'none';
        this.querySelector('.drawflow_uifm_opt_delete').style.display = 'none';
      });
      var self = this;
      document.querySelector("#node-".concat(dataNode.id, " .drawflow_uifm_opt_edit")).addEventListener('click', function () {
        document.querySelectorAll('.drawflow-node').forEach(function (node) {
          if (node.classList.contains('selected')) {
            // Remove the 'selected' class
            node.classList.remove('selected');
          }
        });
        self.selectNode(dataNode.id);
        document.querySelector('.sfdc-nav-tabs a[href="#uiformc-menu-sec1"]').click();
      });
      document.querySelector("#node-".concat(dataNode.id, " .drawflow_uifm_opt_delete")).addEventListener('click', function () {
        self.selectNode(dataNode.id);
        if (self.node_selected) {
          self.dispatch('uifmNodeUnselected', dataNode.data.id);
          self.removeNodeId(self.node_selected.id);
        }
        if (self.connection_selected) {
          self.removeConnection();
        }
        if (self.node_selected != null) {
          self.node_selected.classList.remove('selected');
          self.node_selected = null;
          self.dispatch('nodeUnselected', true);
        }
        if (self.connection_selected != null) {
          self.connection_selected.classList.remove('selected');
          self.removeReouteConnectionSelected();
          self.connection_selected = null;
        }
      });
    }
  }, {
    key: "addRerouteImport",
    value: function addRerouteImport(dataNode) {
      var reroute_width = this.reroute_width;
      var reroute_fix_curvature = this.reroute_fix_curvature;
      var container = this.container;
      Object.keys(dataNode.outputs).map(function (output_item, index) {
        Object.keys(dataNode.outputs[output_item].connections).map(function (input_item, index) {
          var points = dataNode.outputs[output_item].connections[input_item].points;
          if (points !== undefined) {
            points.forEach(function (item, i) {
              var input_id = dataNode.outputs[output_item].connections[input_item].node;
              var input_class = dataNode.outputs[output_item].connections[input_item].output;
              var ele = container.querySelector('.connection.node_in_node-' + input_id + '.node_out_node-' + dataNode.id + '.' + output_item + '.' + input_class);
              if (reroute_fix_curvature) {
                if (i === 0) {
                  for (var z = 0; z < points.length; z++) {
                    var path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path.classList.add('main-path');
                    path.setAttributeNS(null, 'd', '');
                    ele.appendChild(path);
                  }
                }
              }
              var point = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
              point.classList.add('point');
              var pos_x = item.pos_x;
              var pos_y = item.pos_y;
              point.setAttributeNS(null, 'cx', pos_x);
              point.setAttributeNS(null, 'cy', pos_y);
              point.setAttributeNS(null, 'r', reroute_width);
              ele.appendChild(point);
            });
          }
        });
      });
    }
  }, {
    key: "updateNodeValue",
    value: function updateNodeValue(event) {
      var attr = event.target.attributes;
      for (var i = 0; i < attr.length; i++) {
        if (attr[i].nodeName.startsWith('df-')) {
          var keys = attr[i].nodeName.slice(3).split('-');
          var target = this.drawflow.drawflow[this.module].data[event.target.closest('.drawflow_content_node').parentElement.id.slice(5)].data;
          for (var index = 0; index < keys.length - 1; index += 1) {
            if (target[keys[index]] == null) {
              target[keys[index]] = {};
            }
            target = target[keys[index]];
          }
          target[keys[keys.length - 1]] = event.target.value;
          if (event.target.isContentEditable) {
            target[keys[keys.length - 1]] = event.target.innerText;
          }
          this.dispatch('nodeDataChanged', event.target.closest('.drawflow_content_node').parentElement.id.slice(5));
        }
      }
    }
  }, {
    key: "updateNodeNameFromId",
    value: function updateNodeNameFromId(id, newName) {
      var moduleName = this.getModuleFromNodeId(id);
      this.drawflow.drawflow[moduleName].data[id].name = newName;
      this.drawflow.drawflow[moduleName].data[id].html = "<div>".concat(newName, "</div>");
    }
  }, {
    key: "updateNodeDataFromId",
    value: function updateNodeDataFromId(id, data) {
      var moduleName = this.getModuleFromNodeId(id);
      this.drawflow.drawflow[moduleName].data[id].data = data;
      if (this.module === moduleName) {
        var content = this.container.querySelector('#node-' + id);
        Object.entries(data).forEach(function (key, value) {
          if (_typeof(key[1]) === 'object') {
            insertObjectkeys(null, key[0], key[0]);
          } else {
            var elems = content.querySelectorAll('[df-' + key[0] + ']');
            for (var i = 0; i < elems.length; i++) {
              elems[i].value = key[1];
              if (elems[i].isContentEditable) {
                elems[i].innerText = key[1];
              }
            }
          }
        });
        function insertObjectkeys(object, name, completname) {
          if (object === null) {
            var object = data[name];
          } else {
            var object = object[name];
          }
          if (object !== null) {
            Object.entries(object).forEach(function (key, value) {
              if (_typeof(key[1]) === 'object') {
                insertObjectkeys(object, key[0], completname + '-' + key[0]);
              } else {
                var elems = content.querySelectorAll('[df-' + completname + '-' + key[0] + ']');
                for (var i = 0; i < elems.length; i++) {
                  elems[i].value = key[1];
                  if (elems[i].isContentEditable) {
                    elems[i].innerText = key[1];
                  }
                }
              }
            });
          }
        }
      }
    }
  }, {
    key: "addNodeInput",
    value: function addNodeInput(id) {
      var moduleName = this.getModuleFromNodeId(id);
      var infoNode = this.getNodeFromId(id);
      var numInputs = Object.keys(infoNode.inputs).length;
      if (this.module === moduleName) {
        //Draw input
        var input = document.createElement('div');
        input.classList.add('input');
        input.classList.add('input_' + (numInputs + 1));
        var parent = this.container.querySelector('#node-' + id + ' .inputs');
        parent.appendChild(input);
        this.updateConnectionNodes('node-' + id);
      }
      this.drawflow.drawflow[moduleName].data[id].inputs['input_' + (numInputs + 1)] = {
        connections: []
      };
    }
  }, {
    key: "addNodeOutput",
    value: function addNodeOutput(id) {
      var moduleName = this.getModuleFromNodeId(id);
      var infoNode = this.getNodeFromId(id);
      var numOutputs = Object.keys(infoNode.outputs).length;
      if (this.module === moduleName) {
        //Draw output
        var output = document.createElement('div');
        output.classList.add('output');
        output.classList.add('output_' + (numOutputs + 1));
        var parent = this.container.querySelector('#node-' + id + ' .outputs');
        parent.appendChild(output);
        this.updateConnectionNodes('node-' + id);
      }
      this.drawflow.drawflow[moduleName].data[id].outputs['output_' + (numOutputs + 1)] = {
        connections: []
      };
    }
  }, {
    key: "removeNodeInput",
    value: function removeNodeInput(id, input_class) {
      var _this3 = this;
      var moduleName = this.getModuleFromNodeId(id);
      var infoNode = this.getNodeFromId(id);
      if (this.module === moduleName) {
        this.container.querySelector('#node-' + id + ' .inputs .input.' + input_class).remove();
      }
      var removeInputs = [];
      Object.keys(infoNode.inputs[input_class].connections).map(function (key, index) {
        var id_output = infoNode.inputs[input_class].connections[index].node;
        var output_class = infoNode.inputs[input_class].connections[index].input;
        removeInputs.push({
          id_output: id_output,
          id: id,
          output_class: output_class,
          input_class: input_class
        });
      });
      // Remove connections
      removeInputs.forEach(function (item, i) {
        _this3.removeSingleConnection(item.id_output, item.id, item.output_class, item.input_class);
      });
      delete this.drawflow.drawflow[moduleName].data[id].inputs[input_class];

      // Update connection
      var connections = [];
      var connectionsInputs = this.drawflow.drawflow[moduleName].data[id].inputs;
      Object.keys(connectionsInputs).map(function (key, index) {
        connections.push(connectionsInputs[key]);
      });
      this.drawflow.drawflow[moduleName].data[id].inputs = {};
      var input_class_id = input_class.slice(6);
      var nodeUpdates = [];
      connections.forEach(function (item, i) {
        item.connections.forEach(function (itemx, f) {
          nodeUpdates.push(itemx);
        });
        _this3.drawflow.drawflow[moduleName].data[id].inputs['input_' + (i + 1)] = item;
      });
      nodeUpdates = new Set(nodeUpdates.map(function (e) {
        return JSON.stringify(e);
      }));
      nodeUpdates = Array.from(nodeUpdates).map(function (e) {
        return JSON.parse(e);
      });
      if (this.module === moduleName) {
        var eles = this.container.querySelectorAll('#node-' + id + ' .inputs .input');
        eles.forEach(function (item, i) {
          var id_class = item.classList[1].slice(6);
          if (parseInt(input_class_id) < parseInt(id_class)) {
            item.classList.remove('input_' + id_class);
            item.classList.add('input_' + (id_class - 1));
          }
        });
      }
      nodeUpdates.forEach(function (itemx, i) {
        _this3.drawflow.drawflow[moduleName].data[itemx.node].outputs[itemx.input].connections.forEach(function (itemz, g) {
          if (itemz.node == id) {
            var output_id = itemz.output.slice(6);
            if (parseInt(input_class_id) < parseInt(output_id)) {
              if (_this3.module === moduleName) {
                var ele = _this3.container.querySelector('.connection.node_in_node-' + id + '.node_out_node-' + itemx.node + '.' + itemx.input + '.input_' + output_id);
                ele.classList.remove('input_' + output_id);
                ele.classList.add('input_' + (output_id - 1));
              }
              if (itemz.points) {
                _this3.drawflow.drawflow[moduleName].data[itemx.node].outputs[itemx.input].connections[g] = {
                  node: itemz.node,
                  output: 'input_' + (output_id - 1),
                  points: itemz.points
                };
              } else {
                _this3.drawflow.drawflow[moduleName].data[itemx.node].outputs[itemx.input].connections[g] = {
                  node: itemz.node,
                  output: 'input_' + (output_id - 1)
                };
              }
            }
          }
        });
      });
      this.updateConnectionNodes('node-' + id);
    }
  }, {
    key: "removeNodeOutput",
    value: function removeNodeOutput(id, output_class) {
      var _this4 = this;
      var moduleName = this.getModuleFromNodeId(id);
      var infoNode = this.getNodeFromId(id);
      if (this.module === moduleName) {
        this.container.querySelector('#node-' + id + ' .outputs .output.' + output_class).remove();
      }
      var removeOutputs = [];
      Object.keys(infoNode.outputs[output_class].connections).map(function (key, index) {
        var id_input = infoNode.outputs[output_class].connections[index].node;
        var input_class = infoNode.outputs[output_class].connections[index].output;
        removeOutputs.push({
          id: id,
          id_input: id_input,
          output_class: output_class,
          input_class: input_class
        });
      });
      // Remove connections
      removeOutputs.forEach(function (item, i) {
        _this4.removeSingleConnection(item.id, item.id_input, item.output_class, item.input_class);
      });
      delete this.drawflow.drawflow[moduleName].data[id].outputs[output_class];

      // Update connection
      var connections = [];
      var connectionsOuputs = this.drawflow.drawflow[moduleName].data[id].outputs;
      Object.keys(connectionsOuputs).map(function (key, index) {
        connections.push(connectionsOuputs[key]);
      });
      this.drawflow.drawflow[moduleName].data[id].outputs = {};
      var output_class_id = output_class.slice(7);
      var nodeUpdates = [];
      connections.forEach(function (item, i) {
        item.connections.forEach(function (itemx, f) {
          nodeUpdates.push({
            node: itemx.node,
            output: itemx.output
          });
        });
        _this4.drawflow.drawflow[moduleName].data[id].outputs['output_' + (i + 1)] = item;
      });
      nodeUpdates = new Set(nodeUpdates.map(function (e) {
        return JSON.stringify(e);
      }));
      nodeUpdates = Array.from(nodeUpdates).map(function (e) {
        return JSON.parse(e);
      });
      if (this.module === moduleName) {
        var eles = this.container.querySelectorAll('#node-' + id + ' .outputs .output');
        eles.forEach(function (item, i) {
          var id_class = item.classList[1].slice(7);
          if (parseInt(output_class_id) < parseInt(id_class)) {
            item.classList.remove('output_' + id_class);
            item.classList.add('output_' + (id_class - 1));
          }
        });
      }
      nodeUpdates.forEach(function (itemx, i) {
        _this4.drawflow.drawflow[moduleName].data[itemx.node].inputs[itemx.output].connections.forEach(function (itemz, g) {
          if (itemz.node == id) {
            var input_id = itemz.input.slice(7);
            if (parseInt(output_class_id) < parseInt(input_id)) {
              if (_this4.module === moduleName) {
                var ele = _this4.container.querySelector('.connection.node_in_node-' + itemx.node + '.node_out_node-' + id + '.output_' + input_id + '.' + itemx.output);
                ele.classList.remove('output_' + input_id);
                ele.classList.remove(itemx.output);
                ele.classList.add('output_' + (input_id - 1));
                ele.classList.add(itemx.output);
              }
              if (itemz.points) {
                _this4.drawflow.drawflow[moduleName].data[itemx.node].inputs[itemx.output].connections[g] = {
                  node: itemz.node,
                  input: 'output_' + (input_id - 1),
                  points: itemz.points
                };
              } else {
                _this4.drawflow.drawflow[moduleName].data[itemx.node].inputs[itemx.output].connections[g] = {
                  node: itemz.node,
                  input: 'output_' + (input_id - 1)
                };
              }
            }
          }
        });
      });
      this.updateConnectionNodes('node-' + id);
    }
  }, {
    key: "removeNodeId",
    value: function removeNodeId(id) {
      this.removeConnectionNodeId(id);
      var moduleName = this.getModuleFromNodeId(id.slice(5));
      if (this.module === moduleName) {
        this.container.querySelector("#".concat(id)).remove();
      }
      delete this.drawflow.drawflow[moduleName].data[id.slice(5)];
      this.dispatch('nodeRemoved', id.slice(5));
    }
  }, {
    key: "removeConnection",
    value: function removeConnection() {
      if (this.connection_selected != null) {
        var listclass = this.connection_selected.parentElement.classList;
        this.connection_selected.parentElement.remove();
        //console.log(listclass);
        var index_out = this.drawflow.drawflow[this.module].data[listclass[2].slice(14)].outputs[listclass[3]].connections.findIndex(function (item, i) {
          return item.node === listclass[1].slice(13) && item.output === listclass[4];
        });
        this.drawflow.drawflow[this.module].data[listclass[2].slice(14)].outputs[listclass[3]].connections.splice(index_out, 1);
        var index_in = this.drawflow.drawflow[this.module].data[listclass[1].slice(13)].inputs[listclass[4]].connections.findIndex(function (item, i) {
          return item.node === listclass[2].slice(14) && item.input === listclass[3];
        });
        this.drawflow.drawflow[this.module].data[listclass[1].slice(13)].inputs[listclass[4]].connections.splice(index_in, 1);
        this.dispatch('connectionRemoved', {
          output_id: listclass[2].slice(14),
          input_id: listclass[1].slice(13),
          output_class: listclass[3],
          input_class: listclass[4]
        });
        this.connection_selected = null;
      }
    }
  }, {
    key: "removeSingleConnection",
    value: function removeSingleConnection(id_output, id_input, output_class, input_class) {
      var nodeOneModule = this.getModuleFromNodeId(id_output);
      var nodeTwoModule = this.getModuleFromNodeId(id_input);
      if (nodeOneModule === nodeTwoModule) {
        // Check nodes in same module.

        // Check connection exist
        var exists = this.drawflow.drawflow[nodeOneModule].data[id_output].outputs[output_class].connections.findIndex(function (item, i) {
          return item.node == id_input && item.output === input_class;
        });
        if (exists > -1) {
          if (this.module === nodeOneModule) {
            // In same module with view.
            this.container.querySelector('.connection.node_in_node-' + id_input + '.node_out_node-' + id_output + '.' + output_class + '.' + input_class).remove();
          }
          var index_out = this.drawflow.drawflow[nodeOneModule].data[id_output].outputs[output_class].connections.findIndex(function (item, i) {
            return item.node == id_input && item.output === input_class;
          });
          this.drawflow.drawflow[nodeOneModule].data[id_output].outputs[output_class].connections.splice(index_out, 1);
          var index_in = this.drawflow.drawflow[nodeOneModule].data[id_input].inputs[input_class].connections.findIndex(function (item, i) {
            return item.node == id_output && item.input === output_class;
          });
          this.drawflow.drawflow[nodeOneModule].data[id_input].inputs[input_class].connections.splice(index_in, 1);
          this.dispatch('connectionRemoved', {
            output_id: id_output,
            input_id: id_input,
            output_class: output_class,
            input_class: input_class
          });
          return true;
        } else {
          return false;
        }
      } else {
        return false;
      }
    }
  }, {
    key: "removeConnectionNodeId",
    value: function removeConnectionNodeId(id) {
      var idSearchIn = 'node_in_' + id;
      var idSearchOut = 'node_out_' + id;
      var elemsOut = this.container.querySelectorAll(".".concat(idSearchOut));
      for (var i = elemsOut.length - 1; i >= 0; i--) {
        var listclass = elemsOut[i].classList;
        var index_in = this.drawflow.drawflow[this.module].data[listclass[1].slice(13)].inputs[listclass[4]].connections.findIndex(function (item, i) {
          return item.node === listclass[2].slice(14) && item.input === listclass[3];
        });
        this.drawflow.drawflow[this.module].data[listclass[1].slice(13)].inputs[listclass[4]].connections.splice(index_in, 1);
        var index_out = this.drawflow.drawflow[this.module].data[listclass[2].slice(14)].outputs[listclass[3]].connections.findIndex(function (item, i) {
          return item.node === listclass[1].slice(13) && item.output === listclass[4];
        });
        this.drawflow.drawflow[this.module].data[listclass[2].slice(14)].outputs[listclass[3]].connections.splice(index_out, 1);
        elemsOut[i].remove();
        this.dispatch('connectionRemoved', {
          output_id: listclass[2].slice(14),
          input_id: listclass[1].slice(13),
          output_class: listclass[3],
          input_class: listclass[4]
        });
      }
      var elemsIn = this.container.querySelectorAll(".".concat(idSearchIn));
      for (var i = elemsIn.length - 1; i >= 0; i--) {
        var listclass = elemsIn[i].classList;
        var index_out = this.drawflow.drawflow[this.module].data[listclass[2].slice(14)].outputs[listclass[3]].connections.findIndex(function (item, i) {
          return item.node === listclass[1].slice(13) && item.output === listclass[4];
        });
        this.drawflow.drawflow[this.module].data[listclass[2].slice(14)].outputs[listclass[3]].connections.splice(index_out, 1);
        var index_in = this.drawflow.drawflow[this.module].data[listclass[1].slice(13)].inputs[listclass[4]].connections.findIndex(function (item, i) {
          return item.node === listclass[2].slice(14) && item.input === listclass[3];
        });
        this.drawflow.drawflow[this.module].data[listclass[1].slice(13)].inputs[listclass[4]].connections.splice(index_in, 1);
        elemsIn[i].remove();
        this.dispatch('connectionRemoved', {
          output_id: listclass[2].slice(14),
          input_id: listclass[1].slice(13),
          output_class: listclass[3],
          input_class: listclass[4]
        });
      }
    }
  }, {
    key: "getModuleFromNodeId",
    value: function getModuleFromNodeId(id) {
      var nameModule = '';
      var editor = this.drawflow.drawflow;
      Object.keys(editor).map(function (moduleName, index) {
        Object.keys(editor[moduleName].data).map(function (node, index2) {
          if (parseInt(node) == parseInt(id)) {
            nameModule = moduleName;
          }
        });
      });
      return nameModule;
    }
  }, {
    key: "addModule",
    value: function addModule(name) {
      this.drawflow.drawflow[name] = {
        data: {}
      };
      this.dispatch('moduleCreated', name);
    }
  }, {
    key: "changeModule",
    value: function changeModule(name) {
      this.dispatch('moduleChanged', name);
      this.module = name;
      this.precanvas.innerHTML = '';
      this.canvas_x = 0;
      this.canvas_y = 0;
      this.pos_x = 0;
      this.pos_y = 0;
      this.mouse_x = 0;
      this.mouse_y = 0;
      this.zoom = 1;
      this.zoom_last_value = 1;
      this.precanvas.style.transform = '';
      this.import(this.drawflow, false);
    }
  }, {
    key: "removeModule",
    value: function removeModule(name) {
      if (this.module === name) {
        this.changeModule('Home');
      }
      delete this.drawflow.drawflow[name];
      this.dispatch('moduleRemoved', name);
    }
  }, {
    key: "clearModuleSelected",
    value: function clearModuleSelected() {
      this.precanvas.innerHTML = '';
      this.drawflow.drawflow[this.module] = {
        data: {}
      };
    }
  }, {
    key: "clear",
    value: function clear() {
      this.precanvas.innerHTML = '';
      this.drawflow = {
        drawflow: {
          Home: {
            data: {}
          }
        }
      };
    }
  }, {
    key: "export",
    value: function _export() {
      var dataExport = JSON.parse(JSON.stringify(this.drawflow));
      this.dispatch('export', dataExport);
      return dataExport;
    }
  }, {
    key: "getDrawflowArr",
    value: function getDrawflowArr() {
      return JSON.parse(JSON.stringify(this.drawflow));
    }
  }, {
    key: "import",
    value: function _import(data) {
      var notifi = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
      this.clear();
      this.drawflow = JSON.parse(JSON.stringify(data));
      this.load();
      if (notifi) {
        this.dispatch('import', 'import');
      }
    }

    /* Events */
  }, {
    key: "on",
    value: function on(event, callback) {
      // Check if the callback is not a function
      if (typeof callback !== 'function') {
        console.error("The listener callback must be a function, the given type is ".concat(_typeof(callback)));
        return false;
      }
      // Check if the event is not a string
      if (typeof event !== 'string') {
        console.error("The event name must be a string, the given type is ".concat(_typeof(event)));
        return false;
      }
      // Check if this event not exists
      if (this.events[event] === undefined) {
        this.events[event] = {
          listeners: []
        };
      }
      this.events[event].listeners.push(callback);
    }
  }, {
    key: "removeListener",
    value: function removeListener(event, callback) {
      // Check if this event not exists

      if (!this.events[event]) return false;
      var listeners = this.events[event].listeners;
      var listenerIndex = listeners.indexOf(callback);
      var hasListener = listenerIndex > -1;
      if (hasListener) listeners.splice(listenerIndex, 1);
    }
  }, {
    key: "dispatch",
    value: function dispatch(event, details) {
      // Check if this event not exists
      if (this.events[event] === undefined) {
        // console.error(`This event: ${event} does not exist`);
        return false;
      }
      this.events[event].listeners.forEach(function (listener) {
        listener(details);
      });
    }
  }, {
    key: "getUuid",
    value: function getUuid() {
      // http://www.ietf.org/rfc/rfc4122.txt
      var s = [];
      var hexDigits = '0123456789abcdef';
      for (var i = 0; i < 36; i++) {
        s[i] = hexDigits.substr(Math.floor(Math.random() * 0x10), 1);
      }
      s[14] = '4'; // bits 12-15 of the time_hi_and_version field to 0010
      s[19] = hexDigits.substr(s[19] & 0x3 | 0x8, 1); // bits 6-7 of the clock_seq_hi_and_reserved to 01
      s[8] = s[13] = s[18] = s[23] = '-';
      var uuid = s.join('');
      return uuid;
    }
  }]);
}();

/***/ })

/******/ })["default"];
});
//# sourceMappingURL=multistep.js.map