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
        'ip': '192.168.1.52',
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

var _nodeServer = require("./utils/nodeServer");

var _config = require("./config/config");

var ip = _config.Config.getConfig().ip;

var apiEndpoint = _config.Config.getConfig().apiEndpoint;

var nodeServer = new _nodeServer.NodeServer('http://' + ip + '/' + apiEndpoint);
nodeServer.init();

},{"./config/config":1,"./utils/nodeServer":3}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.NodeServer = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var NodeServer = /*#__PURE__*/function () {
  function NodeServer(address) {
    _classCallCheck(this, NodeServer);

    this.nodeAddress = address + '/node';
    this.toggleAddress = this.nodeAddress + '/toggle';
  }

  _createClass(NodeServer, [{
    key: "init",
    value: function init() {
      var _this = this;

      this["switch"] = document.getElementById('node');
      this["switch"].addEventListener('click', function (event) {
        var status = _this.getSwitchStatus(event);

        _this.toggle(status);
      });
      this.status();
      setInterval(function () {
        return _this.status();
      }, 2000);
    }
  }, {
    key: "toggle",
    value: function toggle(status) {
      fetch(this.toggleAddress + '/' + status).then(function (res) {
        return res.json();
      }).then(function (status) {
        console.log(status);
      })["catch"](function (err) {
        console.error(err);
      });
    }
  }, {
    key: "status",
    value: function status() {
      var _this2 = this;

      fetch(this.nodeAddress + '/status').then(function (res) {
        return res.json();
      }).then(function (status) {
        _this2.setSwitchStatus(status);
      })["catch"](function (err) {
        console.error(err);
      });
    }
  }, {
    key: "getSwitchStatus",
    value: function getSwitchStatus(event) {
      return event.currentTarget.checked ? 'on' : 'off';
    }
  }, {
    key: "setSwitchStatus",
    value: function setSwitchStatus(status) {
      this["switch"].checked = status === 'on' ? this["switch"].checked = true : this["switch"].checked = false;
    }
  }]);

  return NodeServer;
}();

exports.NodeServer = NodeServer;

},{}]},{},[2]);
