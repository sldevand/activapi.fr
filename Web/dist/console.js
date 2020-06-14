(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.Config = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Config = /*#__PURE__*/function () {
  function Config() {
    _classCallCheck(this, Config);
  }

  _createClass(Config, null, [{
    key: "getConfig",
    value: function getConfig() {
      return {
        'ip': 'localhost',
        'apiEndpoint': 'activapi/api',
        'port': '5901'
      };
    }
  }]);

  return Config;
}();

exports.Config = Config;

},{}],2:[function(require,module,exports){
"use strict";

var _consoleComponent = require("./console/console-component");

var _config = require("./config/config");

var ip = _config.Config.getConfig().ip;

var apiEndpoint = _config.Config.getConfig().apiEndpoint;

var console = new _consoleComponent.Console('http://' + ip + '/' + apiEndpoint);
console.init();

},{"./config/config":1,"./console/console-component":3}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.Console = void 0;

var _consoleTemplate = require("./templates/console-template");

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Console = /*#__PURE__*/function () {
  function Console(address) {
    _classCallCheck(this, Console);

    this.nodeAddress = address + '/node';
  }

  _createClass(Console, [{
    key: "init",
    value: function init() {
      var _this = this;

      var display = document.querySelector('#console-display');
      var period = document.querySelector('#period').value;
      fetch(this.nodeAddress + "/log/" + period).then(function (data) {
        return data.json();
      }).then(function (logs) {
        _this.logs = logs.messages;
        display.innerHTML = _this.createDisplayTemplate();
      })["catch"](function (err) {
        return console.log(err);
      });
    }
  }, {
    key: "createDisplayTemplate",
    value: function createDisplayTemplate() {
      return _consoleTemplate.ConsoleTemplate.render(this.logs);
    }
  }]);

  return Console;
}();

exports.Console = Console;

},{"./templates/console-template":4}],4:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.ConsoleTemplate = void 0;

function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var ConsoleTemplate = /*#__PURE__*/function () {
  function ConsoleTemplate() {
    _classCallCheck(this, ConsoleTemplate);
  }

  _createClass(ConsoleTemplate, null, [{
    key: "render",
    value: function render(logs) {
      var template = '';
      var date = new Date();

      var _iterator = _createForOfIteratorHelper(logs),
          _step;

      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var log = _step.value;
          date.setTime(parseInt(log.createdAt) * 1000);
          template += "<span>".concat(date.toLocaleTimeString("fr-FR", {
            timeZone: "Europe/Paris"
          }), " ").concat(log.content, "</span> <br>");
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }

      return template;
    }
  }]);

  return ConsoleTemplate;
}();

exports.ConsoleTemplate = ConsoleTemplate;

},{}]},{},[2]);
