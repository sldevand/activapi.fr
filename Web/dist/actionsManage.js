(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.Actions = void 0;

var _actionTemplate = require("./templates/action-template");

var _actionneurSelectTemplate = require("./templates/actionneur-select-template");

var _apiManage = require("../utils/apiManage");

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Actions =
/*#__PURE__*/
function () {
  function Actions() {
    _classCallCheck(this, Actions);
  }

  _createClass(Actions, [{
    key: "init",
    value: function init() {
      var _this = this;

      var actionId = document.querySelector('#id').getAttribute('value');
      fetch("api/actions/" + actionId).then(function (data) {
        return data.json();
      }).then(function (action) {
        _this.action = action;
        document.querySelector('#action-content').innerHTML = _this.createActionTemplate();
        return fetch('api/actionneurs/');
      }).then(function (data) {
        return data.json();
      }).then(function (actionneurs) {
        if (actionneurs.error) {
          Materialize.toast(actionneurs.error, 2000);
          return;
        }

        _this.actionneurs = actionneurs;

        _this.addRow(_this.action.actionneurId);

        _this.initForm();
      }).catch(function (err) {
        return console.log(err);
      });
    }
  }, {
    key: "addRow",
    value: function addRow() {
      var actionneurId = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
      var actionneurs = document.querySelector('#action-content #actionneur');
      var elt = document.createElement('div');
      elt.classList.add('row');
      elt.id = 'actionneur-row-' + actionneurId;

      var row = _actionneurSelectTemplate.ActionneurRowTemplate.render(this.action, this.actionneurs, actionneurId);

      if (!row) {
        return;
      }

      elt.innerHTML = row;
      actionneurs.appendChild(elt);
      $('select').material_select();
    }
  }, {
    key: "createActionTemplate",
    value: function createActionTemplate() {
      return _actionTemplate.ActionTemplate.render(this.action);
    }
  }, {
    key: "initForm",
    value: function initForm() {
      var _this2 = this;

      var form = document.forms[0];
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        document.getElementById('submit').disabled = true;
        var apiManage = new _apiManage.ApiManage(form.getAttribute('method'), form.getAttribute('action'));
        var formData = new FormData(form);
        var object = {};
        formData.forEach(function (value, key) {
          object[key] = value;
        });
        apiManage.sendObject(JSON.stringify(object), function (request) {
          _this2.responseManagement(request);
        });
      });
    }
  }, {
    key: "responseManagement",
    value: function responseManagement(request) {
      var jsonResponse = JSON.parse(request.response);
      this.dispatchResponse(request.status, jsonResponse);
    }
  }, {
    key: "dispatchResponse",
    value: function dispatchResponse(status, jsonResponse) {
      var crudOperation = '';

      switch (status) {
        case 202:
          crudOperation = "updated";
          break;

        case 201:
          crudOperation = "created";
          break;

        case 204:
          crudOperation = "deleted";
          break;

        default:
          return Materialize.toast(jsonResponse['error'], 2000);
      }

      return this.makeToast(jsonResponse, crudOperation);
    }
  }, {
    key: "makeToast",
    value: function makeToast(jsonResponse, crudOperation) {
      return Materialize.toast(jsonResponse.nom + " " + crudOperation, 700, '', function () {
        window.location.replace('actions');
      });
    }
  }]);

  return Actions;
}();

exports.Actions = Actions;

},{"../utils/apiManage":5,"./templates/action-template":2,"./templates/actionneur-select-template":3}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.ActionTemplate = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var ActionTemplate =
/*#__PURE__*/
function () {
  function ActionTemplate() {
    _classCallCheck(this, ActionTemplate);
  }

  _createClass(ActionTemplate, null, [{
    key: "render",
    value: function render(action) {
      console.log(action);

      if (!action || action.error) {
        action.id = 0;
        action.nom = '';
      }

      action.id = action.id || 0;
      action.nom = action.nom || '';
      return "\n    <div class=\"row\">\n        <div class=\"col s8\">\n            <label for=\"action-name-".concat(action.id, "\" class=\"active\">Nom</label>\n            <input type=\"text\" name=\"nom\" id=\"action-name-").concat(action.id, "\" value=\"").concat(action.nom, "\" required>\n        </div>\n    </div>\n    <div class=\"row\">\n        <div id=\"actionneur\" class=\"col s6\"></div>\n        <div class=\"col s3\">\n            <label for=\"etat\" class=\"active\">Etat</label>\n            <input type=\"number\" name=\"etat\" id=\"etat\" value=\"").concat(action.etat, "\" required>\n        </div>\n         <div class=\"col s3\">\n            <label for=\"timeout\" class=\"active\">Timeout</label>\n            <input type=\"number\" name=\"timeout\" id=\"timeout\" value=\"").concat(action.timeout, "\" required>\n        </div>\n    </div>\n");
    }
  }]);

  return ActionTemplate;
}();

exports.ActionTemplate = ActionTemplate;

},{}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.ActionneurRowTemplate = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var ActionneurRowTemplate =
/*#__PURE__*/
function () {
  function ActionneurRowTemplate() {
    _classCallCheck(this, ActionneurRowTemplate);
  }

  _createClass(ActionneurRowTemplate, null, [{
    key: "render",
    value: function render(action, actionneurs) {
      var idSelected = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : null;

      if (!action || !actionneurs) {
        return;
      }

      var template = "\n<div class=\"col s12\">\n    <label for=\"action-select\">Actionneur</label>\n    <div class=\"select-wrapper\"><span class=\"caret\">\u25BC</span>\n        <select name=\"actionneurId\" id=\"actionneur-select\">";
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = actionneurs[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var actionneur = _step.value;
          var selected = '';

          if (actionneur.id === idSelected) {
            selected = 'selected';
          }

          template += "<option value=\"".concat(actionneur.id, "\" ").concat(selected, ">").concat(actionneur.nom, "</option>");
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

      template += "</select>\n    </div>\n</div>\n";
      return template;
    }
  }]);

  return ActionneurRowTemplate;
}();

exports.ActionneurRowTemplate = ActionneurRowTemplate;

},{}],4:[function(require,module,exports){
"use strict";

var _actionComponent = require("./actions/action-component");

$(document).ready(function () {
  $('select').material_select();
});
var actions = new _actionComponent.Actions();
actions.init();

},{"./actions/action-component":1}],5:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.ApiManage = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var ApiManage =
/*#__PURE__*/
function () {
  function ApiManage(method, action) {
    var jsonHeader = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;

    _classCallCheck(this, ApiManage);

    this.request = new XMLHttpRequest();
    this.method = method;
    this.action = action;
    this.jsonHeader = jsonHeader;
  }

  _createClass(ApiManage, [{
    key: "setJsonHeader",
    value: function setJsonHeader() {
      this.request.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');
    }
  }, {
    key: "sendObject",
    value: function sendObject(object, callback) {
      var _this = this;

      this.request.onreadystatechange = function () {
        if (_this.request.readyState !== 4) {
          return;
        }

        callback(_this.request);
      };

      this.request.open(this.method, this.action);

      if (this.jsonHeader) {
        this.setJsonHeader();
      }

      this.request.send(object);
    }
  }]);

  return ApiManage;
}();

exports.ApiManage = ApiManage;

},{}]},{},[4]);
