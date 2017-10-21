/**
 * Created by leon on 15/10/14.
 */
Template7.registerHelper('if_compare', function (a, operator, b, options) {
    var match = false;
    if ((operator === '==' && a == b) || (operator === '===' && a === b) || (operator === '!=' && a != b) || (operator === '>' && a > b) || (operator === '<' && a < b) || (operator === '>=' && a >= b) || (operator === '<=' && a <= b)) {
        match = true;
    }
    if (match) return options.fn(this); else return options.inverse(this);
});