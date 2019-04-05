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

      var scenarioId = document.querySelector('#scenarioid').getAttribute('value');
      fetch("api/scenarios/" + scenarioId).then(function (data) {
        return data.json();
      }).then(function (scenario) {
        _this.scenario = scenario;
        document.querySelector('#scenario-content').innerHTML = _this.createScenarioTemplate();
        return fetch('api/sequences/');
      }).then(function (data) {
        return data.json();
      }).then(function (sequences) {
        _this.sequences = sequences;

        _this.initSequenceAddListener();
      }).catch(function (err) {
        return console.log(err);
      });
    }
  }, {
    key: "addRow",
    value: function addRow() {
      var sequences = document.querySelector('#sequences');
      var elt = document.createElement('div');
      elt.classList.add('row');
      elt.id = 'delete-button';

      var row = _sequenceSelectTemplate.SequenceRowTemplate.render(this.scenario, this.sequences);

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
      var sequences = document.querySelector('#sequences');
      var elt = document.createElement('input');
      elt.setAttribute('value', itemId);
      elt.setAttribute('name', 'actionneurs[deleteId][]');
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
    value: function initRemoveButton(deleteButton) {
      var _this3 = this;

      deleteButton.addEventListener('click', function (e) {
        e.preventDefault();

        _this3.removeRow(e.target.parentNode);

        _this3.addDeletionInput(e.target.parentNode.dataset.sequenceid);
      });
    }
  }, {
    key: "createScenarioTemplate",
    value: function createScenarioTemplate() {
      return _scenarioTemplate.ScenarioTemplate.render(this.scenario);
    }
  }]);

  return Scenarios;
}();

exports.Scenarios = Scenarios;

},{"./templates/scenario-template":3,"./templates/sequence-select-template":4}],3:[function(require,module,exports){
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
      return "\n    <div class=\"row\">\n        <div class=\"col s8\">\n            <label for=\"scenario-name-".concat(scenario.id, " ?>\" class=\"active\">Nom</label>\n            <input type=\"text\" name=\"nom\" id=\"scenario-name-").concat(scenario.id, " ?>\" value=\"").concat(scenario.nom, "\" required>\n        </div>\n    </div>\n    <div class=\"row\">\n        <div id=\"sequences\" class=\"s12\"></div>\n    </div>\n");
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
    value: function render(scenario, sequences) {
      if (!scenario || !sequences) {
        return;
      }

      console.log(scenario, sequences);
      var template = "\n<div class=\"col s6\">\n    <label for=\"sequence-select-\">Sequence</label>\n    <div class=\"select-wrapper\"><span class=\"caret\">\u25BC</span>\n        <select name=\"sequences[sequenceid][]\" id=\"sequence-select-\">";
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = sequences[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var sequence = _step.value;
          template += "<option value=\"".concat(sequence.id, "\" >").concat(sequence.nom, "</option>");
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

      template += "</select>\n    </div>\n</div>\n\n<label class=\"active\" for=\"scenario-sequence-delete\">*</label>\n<i id=\"scenario-sequence-delete\" class=\"material-icons secondaryTextColor col s2 delete\">delete</i>\n";
      return template;
    }
  }]);

  return SequenceRowTemplate;
}();

exports.SequenceRowTemplate = SequenceRowTemplate;

},{}]},{},[1]);
