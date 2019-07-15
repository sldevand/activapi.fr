(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

var _scenarioComponent = require("./scenarios/scenario-component");

$(document).ready(function () {
  $('select').material_select();
});
var scenarios = new _scenarioComponent.Scenarios();
scenarios.init();

},{"./scenarios/scenario-component":2}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.Scenarios = void 0;

var _scenarioTemplate = require("./templates/scenario-template");

var _sequenceSelectTemplate = require("./templates/sequence-select-template");

var _apiManage = require("../utils/apiManage");

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var Scenarios =
/*#__PURE__*/
function () {
  function Scenarios() {
    _classCallCheck(this, Scenarios);
  }

  _createClass(Scenarios, [{
    key: "init",
    value: function init() {
      var _this = this;

      var scenarioId = document.querySelector('#id').getAttribute('value');
      fetch("api/scenarios/" + scenarioId).then(function (data) {
        return data.json();
      }).then(function (scenario) {
        _this.scenario = scenario;
        document.querySelector('#scenario-content').innerHTML = _this.createScenarioTemplate();
        return fetch('api/sequences/');
      }).then(function (data) {
        return data.json();
      }).then(function (sequences) {
        if (sequences.error) {
          Materialize.toast(sequences.error, 2000);
          return;
        }

        _this.sequences = sequences;

        for (var scenarioSequenceId in _this.scenario.sequences) {
          var sequenceId = _this.scenario.sequences[scenarioSequenceId].id;

          _this.addRow(scenarioSequenceId, sequenceId);
        }

        _this.initSequenceAddListener();

        _this.initForm();
      }).catch(function (err) {
        return console.log(err);
      });
    }
  }, {
    key: "addRow",
    value: function addRow() {
      var scenarioSequenceId = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : null;
      var selectedSequenceId = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
      var sequences = document.querySelector('#scenario-content #sequences');
      var elt = document.createElement('div');
      elt.classList.add('row');
      elt.id = 'sequence-row-' + scenarioSequenceId;

      var row = _sequenceSelectTemplate.SequenceRowTemplate.render(this.scenario, this.sequences, scenarioSequenceId, selectedSequenceId);

      if (!row) {
        return;
      }

      elt.innerHTML = row;
      sequences.appendChild(elt);
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
      var sequences = document.querySelector('#scenario-content #sequences');
      var elt = document.createElement('input');
      elt.setAttribute('value', itemId);
      elt.setAttribute('name', 'deleted-scenarioSequence-' + itemId);
      elt.hidden = true;
      sequences.appendChild(elt);
    }
  }, {
    key: "initSequenceAddListener",
    value: function initSequenceAddListener() {
      var _this2 = this;

      var sequenceAdd = document.querySelector('#sequence-add');
      sequenceAdd.addEventListener('click', function (e) {
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
    key: "createScenarioTemplate",
    value: function createScenarioTemplate() {
      return _scenarioTemplate.ScenarioTemplate.render(this.scenario);
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
        object.scenarioSequences = [];
        object.deletedScenarioSequences = [];
        formData.forEach(function (value, key) {
          if (key.startsWith('sequence-')) {
            var scenarioSequenceId = key.split('-')[1];
            object.scenarioSequences.push({
              "id": scenarioSequenceId,
              "sequenceId": value
            });
          } else if (key.startsWith('deleted-scenarioSequence-')) {
            object.deletedScenarioSequences.push(value);
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
        window.location.replace('scenarios');
      });
    }
  }]);

  return Scenarios;
}();

exports.Scenarios = Scenarios;

},{"../utils/apiManage":5,"./templates/scenario-template":3,"./templates/sequence-select-template":4}],3:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.ScenarioTemplate = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var ScenarioTemplate =
/*#__PURE__*/
function () {
  function ScenarioTemplate() {
    _classCallCheck(this, ScenarioTemplate);
  }

  _createClass(ScenarioTemplate, null, [{
    key: "render",
    value: function render(scenario) {
      if (!scenario || scenario.error) {
        scenario.id = 0;
        scenario.nom = '';
      }

      scenario.id = scenario.id || 0;
      scenario.nom = scenario.nom || '';
      return "\n    <div class=\"row\">\n        <div class=\"col s6\">\n            <label for=\"scenario-name-".concat(scenario.id, "\" class=\"active\">Nom</label>\n            <input type=\"text\" name=\"nom\" id=\"scenario-name-").concat(scenario.id, "\" value=\"").concat(scenario.nom, "\" required>\n        </div>\n        <div class=\"col s6\">\n            <label for=status\" class=\"active\">Statut</label>\n            <div class=\"select-wrapper\"><span class=\"caret\">\u25BC</span>\n                <select name=\"status\" id=\"status\">               \n                   <option value=\"play\" selected>Play</option>\n                   <option value=\"stop\" selected>Stop</option>                   \n                </select>\n            </div>\n        </div>        \n    </div>\n    <div class=\"row\">\n        <div id=\"sequences\" class=\"s12\"></div>\n    </div>\n");
    }
  }]);

  return ScenarioTemplate;
}();

exports.ScenarioTemplate = ScenarioTemplate;

},{}],4:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.SequenceRowTemplate = void 0;

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

var SequenceRowTemplate =
/*#__PURE__*/
function () {
  function SequenceRowTemplate() {
    _classCallCheck(this, SequenceRowTemplate);
  }

  _createClass(SequenceRowTemplate, null, [{
    key: "render",
    value: function render(scenario, sequences, scenarioSequenceId) {
      var idSelected = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : null;

      if (!scenario || !sequences) {
        return;
      }

      var template = "\n<div class=\"col s6\">\n    <label for=\"sequence-select-".concat(scenarioSequenceId, "\">Sequence</label>\n    <div class=\"select-wrapper\"><span class=\"caret\">\u25BC</span>\n        <select name=\"sequence-").concat(scenarioSequenceId, "\" id=\"sequence-select-").concat(scenarioSequenceId, "\">");
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = sequences[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var sequence = _step.value;
          var selected = '';

          if (sequence.id === idSelected) {
            selected = 'selected';
          }

          template += "<option value=\"".concat(sequence.id, "\" ").concat(selected, ">").concat(sequence.nom, "</option>");
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

      template += "</select>\n    </div>\n</div>\n\n<i id=\"scenario-sequence-delete\" data-id=\"".concat(scenarioSequenceId, "\" class=\"material-icons secondaryTextColor col s2 delete\">delete</i>\n");
      return template;
    }
  }]);

  return SequenceRowTemplate;
}();

exports.SequenceRowTemplate = SequenceRowTemplate;

},{}],5:[function(require,module,exports){
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

},{}]},{},[1]);
