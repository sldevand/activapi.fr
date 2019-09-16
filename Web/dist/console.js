(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.Config = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Config =
/*#__PURE__*/
function () {
  function Config() {
    _classCallCheck(this, Config);
  }

  _createClass(Config, null, [{
    key: "getConfig",
    value: function getConfig() {
      return {
        'ip': '192.168.1.52',
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

var console = new _consoleComponent.Console('http://' + ip + '/activapi.fr/api');
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

var Console =
/*#__PURE__*/
function () {
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
      }).catch(function (err) {
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

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var ConsoleTemplate =
/*#__PURE__*/
function () {
  function ConsoleTemplate() {
    _classCallCheck(this, ConsoleTemplate);
  }

  _createClass(ConsoleTemplate, null, [{
    key: "render",
    value: function render(logs) {
      var template = '';
      var date = new Date();
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = logs[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var log = _step.value;
          date.setTime(parseInt(log.createdAt) * 1000);
          template += "<span>".concat(date.toLocaleTimeString("fr-FR", {
            timeZone: "Europe/Paris"
          }), " ").concat(log.content, "</span> <br>");
        }
      } catch (err) {
        _didIteratorError = true;
        _iteratorError = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion && _iterator.return != null) {
            _iterator.return();
          }
        } finally {
          if (_didIteratorError) {
            throw _iteratorError;
          }
        }
      }

      return template;
    }
  }]);

  return ConsoleTemplate;
}();

exports.ConsoleTemplate = ConsoleTemplate;

},{}]},{},[2]);
