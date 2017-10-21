(function () {
  'use strict';

  angular
    .module('yike.subMenu', [])
    .directive('yikeSubMenu', yikeSubMenu);

  yikeSubMenu.$inject = [];
  function yikeSubMenu() {
    return {
      replace: false,
      restrict: 'AE',
      link: function (scope, elem, attrs) {
        scope.clickCategory = function (key) {
          scope.current.menu = key == scope.current.menu ? '' : key;
          scope.current.subMenu = [];
        };

        scope.clickMenu = function (menu) {
          if (menu.sub.length > 0) {
            scope.current.subMenu = menu.sub;
          } else {
            scope.condition[scope.current.menu] = menu;
            scope.current.menu = null;
            scope.page = 1;
            scope.query();
          }
          $('.sub').scrollTop(0);
        };

        scope.clickSubMenu = function (subMenu) {
          scope.condition[scope.current.menu] = subMenu;
          scope.current.menu = null;
          scope.page = 1;
          scope.query();
        }
      },
      templateUrl: STATIC_PATH +  'templates/utils/sub-menu.html'
    };
  }
})();
