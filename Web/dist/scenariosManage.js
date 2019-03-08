(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

var _scenarios = require("./scenarios/scenarios");

$(document).ready(function () {
  $('select').material_select();
});
var scenarios = new _scenarios.Scenarios();
scenarios.init();
;

},{"./scenarios/scenarios":2}],2:[function(require,module,exports){
"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports.Scenarios = void 0;

var _sequenceRow = require("./sequenceRow");

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

      fetch('api/actionneurs/').then(function (data) {
        return data.json();
      }).then(function (actionneurs) {
        _this.actionneurs = actionneurs;
        var scenario = document.querySelector('#scenarioid');
        var scenarioid = scenario.getAttribute('value');
        return fetch('api/scenarios/' + scenarioid);
      }).then(function (data) {
        return data.json();
      }).then(function (scenarios) {
        console.log(scenarios);
        _this.scenarios = scenarios; // this.addRows();

        _this.initSequenceAddListener();

        var deleteButtons = document.getElementsByClassName('delete');
        var _iteratorNormalCompletion = true;
        var _didIteratorError = false;
        var _iteratorError = undefined;

        try {
          for (var _iterator = deleteButtons[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
            var deleteButton = _step.value;

            _this.initRemoveButton(deleteButton);
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
      }).catch(function (err) {
        return console.log(err);
      });
    }
  }, {
    key: "addRows",
    value: function addRows() {
      var _iteratorNormalCompletion2 = true;
      var _didIteratorError2 = false;
      var _iteratorError2 = undefined;

      try {
        for (var _iterator2 = this.scenarios[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
          var scenario = _step2.value;
          this.addRow(scenario);
        }
      } catch (err) {
        _didIteratorError2 = true;
        _iteratorError2 = err;
      } finally {
        try {
          if (!_iteratorNormalCompletion2 && _iterator2.return != null) {
            _iterator2.return();
          }
        } finally {
          if (_didIteratorError2) {
            throw _iteratorError2;
          }
        }
      }
    }
  }, {
    key: "addRow",
    value: function addRow(scenario) {
      var sequences = document.querySelector('#sequences');
      var elt = document.createElement('div');
      elt.classList.add('row');
      elt.id = 'delete-button';
      elt.innerHTML = this.createRow(scenario);
      sequences.appendChild(elt);
      $('select').material_select();
      this.initRemoveButton(elt.id);
    }
  }, {
    key: "createRow",
    value: function createRow(scenario) {
      return _sequenceRow.SequenceRowTemplate.render(this.actionneurs, scenario);
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
        _this2.addRow();
      });
    }
  }, {
    key: "initRemoveButton",
    value: function initRemoveButton(deleteButton) {
      var _this3 = this;

      deleteButton.addEventListener('click', function (e) {
        _this3.removeRow(e.target.parentNode);

        _this3.addDeletionInput(e.target.parentNode.id);
      });
    }
  }]);

  return Scenarios;
}();

exports.Scenarios = Scenarios;

},{"./sequenceRow":3}],3:[function(require,module,exports){
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
    value: function render(actionneurs, scenario) {
      var template = "\n<div class=\"col s2\">\n    <label for=\"actionneur-id-\" class=\"active\">ItemId</label>\n    <input type=\"text\" name=\"actionneurs[id][]\" id=\"actionneur-id-\" value=\"\" readonly=\"\" required=\"\">\n</div>\n\n<div class=\"col s6\">\n    <label for=\"actionneur-select-\">Actionneur</label>\n    <div class=\"select-wrapper\"><span class=\"caret\">\u25BC</span>\n        <select name=\"actionneurs[actionneurid][]\" id=\"actionneur-select-\">";
      var _iteratorNormalCompletion = true;
      var _didIteratorError = false;
      var _iteratorError = undefined;

      try {
        for (var _iterator = actionneurs[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
          var actionneur = _step.value;
          template += "<option value=\"".concat(actionneur.id, "\" >").concat(actionneur.nom, "</option>");
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

      template += "</select>\n    </div>\n</div>\n\n<div class=\"col s2\">\n    <label for=\"actionneur-etat-\" class=\"active\">Etat</label>\n    <input type=\"number\" name=\"actionneurs[etat][]\" id=\"actionneur-etat-\" value=\"0\" min=\"0\" max=\"255\" step=\"1\">\n</div>\n\n<label class=\"active\" for=\"scenario-sequence-delete\">*</label>\n<i id=\"scenario-sequence-delete-".concat(scenario.id, "\" class=\"material-icons secondaryTextColor col s2 delete\">delete</i>\n");
      return template;
    }
  }]);

  return SequenceRowTemplate;
}();

exports.SequenceRowTemplate = SequenceRowTemplate;

},{}]},{},[1]);
