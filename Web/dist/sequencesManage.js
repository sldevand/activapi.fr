(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.Sequences = void 0;

var _sequenceTemplate = require("./templates/sequence-template");

var _actionsSelectTemplate = require("./templates/actions-select-template");

var _apiManage = require("../utils/apiManage");

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Sequences =
/*#__PURE__*/
function () {
  function Sequences() {
    _classCallCheck(this, Sequences);
  }

  _createClass(Sequences, [{
    key: "init",
    value: function init() {
      var _this = this;

      var sequenceId = document.querySelector('#id').getAttribute('value');
      fetch("api/sequences/" + sequenceId).then(function (data) {
        return data.json();
      }).then(function (sequence) {
        _this.sequence = sequence;
        document.querySelector('#sequence-content').innerHTML = _this.createSequenceTemplate();
        return fetch('api/actions/');
      }).then(function (data) {
        return data.json();
      }).then(function (actions) {
        if (actions.error) {
          Materialize.toast(actions.error, 2000);
          return;
        }

        _this.actions = actions;

        for (var sequenceActionId in _this.sequence.actions) {
          var actionId = _this.sequence.actions[sequenceActionId].id;
          console.log(_this.sequence.actions);

          _this.addRow(sequenceActionId, actionId);
        }

        _this.initActionAddListener();

        _this.initForm();
      }).catch(function (err) {
        return console.log(err);
      });
    }
  }, {
    key: "addRow",
    value: function addRow() {
      var sequenceActionId = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
      var selectedActionId = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
      var actions = document.querySelector('#sequence-content #actions');
      var elt = document.createElement('div');
      elt.classList.add('row');
      elt.id = 'action-row-' + sequenceActionId;

      var row = _actionsSelectTemplate.ActionRowTemplate.render(this.sequence, this.actions, sequenceActionId, selectedActionId);

      if (!row) {
        return;
      }

      elt.innerHTML = row;
      actions.appendChild(elt);
      $('select').material_select();
      this.initRemoveButton(elt.id);
    }
  }, {
    key: "removeRow",
    value: function removeRow(target) {
      target.remove();
    }
  }, {
    key: "addDeletionInput",
    value: function addDeletionInput(itemId) {
      var actions = document.querySelector('#sequence-content #actions');
      var elt = document.createElement('input');
      elt.setAttribute('value', itemId);
      elt.setAttribute('name', 'deleted-sequenceAction-' + itemId);
      elt.hidden = true;
      actions.appendChild(elt);
    }
  }, {
    key: "initActionAddListener",
    value: function initActionAddListener() {
      var _this2 = this;

      var actionAdd = document.querySelector('#action-add');
      actionAdd.addEventListener('click', function (e) {
        e.preventDefault();

        _this2.addRow();
      });
    }
  }, {
    key: "initRemoveButton",
    value: function initRemoveButton(domId) {
      var _this3 = this;

      var deleteButton = document.querySelector('#' + domId);
      deleteButton.addEventListener('click', function (e) {
        e.preventDefault();

        _this3.removeRow(e.target.parentNode);

        if (e.target.dataset.id !== 'null') {
          _this3.addDeletionInput(e.target.dataset.id);
        }
      });
    }
  }, {
    key: "createSequenceTemplate",
    value: function createSequenceTemplate() {
      return _sequenceTemplate.SequenceTemplate.render(this.sequence);
    }
  }, {
    key: "initForm",
    value: function initForm() {
      var _this4 = this;

      var form = document.forms[0];
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        document.getElementById('submit').disabled = true;
        var apiManage = new _apiManage.ApiManage(form.getAttribute('method'), form.getAttribute('action'));
        var formData = new FormData(form);
        var object = {};
        object.sequenceActions = [];
        object.deletedSequenceActions = [];
        formData.forEach(function (value, key) {
          if (key.startsWith('action-')) {
            var sequenceActionId = key.split('-')[1];
            object.sequenceActions.push({
              "id": sequenceActionId,
              "actionId": value
            });
          } else if (key.startsWith('deleted-sequenceAction-')) {
            object.deletedSequenceActions.push(value);
          } else {
            object[key] = value;
          }
        });
        apiManage.sendObject(JSON.stringify(object), function (request) {
          _this4.responseManagement(request);
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
        window.location.replace('sequences');
      });
    }
  }]);

  return Sequences;
}();

exports.Sequences = Sequences;

},{"../utils/apiManage":5,"./templates/actions-select-template":2,"./templates/sequence-template":3}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.ActionRowTemplate = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var ActionRowTemplate =
/*#__PURE__*/
function () {
  function ActionRowTemplate() {
    _classCallCheck(this, ActionRowTemplate);
  }

  _createClass(ActionRowTemplate, null, [{
    key: "render",
    value: function render(sequence, actions, sequenceActionId) {
      var idSelected = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;

      if (!sequence || !actions) {
        return;
      }

      var template = "\n<div class=\"col s6\">\n    <label for=\"action-select-".concat(sequenceActionId, "\">Action</label>\n    <div class=\"select-wrapper\"><span class=\"caret\">\u25BC</span>\n        <select name=\"action-").concat(sequenceActionId, "\" id=\"action-select-").concat(sequenceActionId, "\">");
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = actions[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var action = _step.value;
          var selected = '';

          if (action.id === idSelected) {
            selected = 'selected';
          }

          template += "<option value=\"".concat(action.id, "\" ").concat(selected, ">").concat(action.nom, "</option>");
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

      template += "</select>\n    </div>\n</div>\n\n<i id=\"sequence-action-delete\" data-id=\"".concat(sequenceActionId, "\" class=\"material-icons secondaryTextColor col s2 delete\">delete</i>\n");
      return template;
    }
  }]);

  return ActionRowTemplate;
}();

exports.ActionRowTemplate = ActionRowTemplate;

},{}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.SequenceTemplate = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var SequenceTemplate =
/*#__PURE__*/
function () {
  function SequenceTemplate() {
    _classCallCheck(this, SequenceTemplate);
  }

  _createClass(SequenceTemplate, null, [{
    key: "render",
    value: function render(sequence) {
      if (!sequence || sequence.error) {
        sequence.id = 0;
        sequence.nom = '';
      }

      sequence.id = sequence.id || 0;
      sequence.nom = sequence.nom || '';
      return "\n    <div class=\"row\">\n        <div class=\"col s8\">\n            <label for=\"sequence-name-".concat(sequence.id, "\" class=\"active\">Nom</label>\n            <input type=\"text\" name=\"nom\" id=\"sequence-name-").concat(sequence.id, "\" value=\"").concat(sequence.nom, "\" required>\n        </div>\n    </div>\n    <div class=\"row\">\n        <div id=\"actions\" class=\"s12\"></div>\n    </div>\n");
    }
  }]);

  return SequenceTemplate;
}();

exports.SequenceTemplate = SequenceTemplate;

},{}],4:[function(require,module,exports){
"use strict";

var _sequenceComponent = require("./sequences/sequence-component");

$(document).ready(function () {
  $('select').material_select();
});
var sequences = new _sequenceComponent.Sequences();
sequences.init();

},{"./sequences/sequence-component":1}],5:[function(require,module,exports){
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
