(function () {
  'use strict';

  angular
    .module('yike.utils', ['ionic'])
    .factory('$yikeUtils', $yikeUtils);

  $yikeUtils.$inject = ['$rootScope', '$state', '$ionicPopup', '$ionicModal', '$location', '$timeout', 'ionicToast', '$ionicLoading'];

  /* @ngInject */
  function $yikeUtils($rootScope, $state, $ionicPopup, $ionicModal, $location, $timeout, ionicToast, $ionicLoading) {
    return {
      go: go,
      alert: alert,
      confirm: confirm,
      show: show,
      toast: toast
    };

    ////////////////

    function go(target, params, options) {
      $state.go(target, params, options);
    }

    function toast(message, position, stick, time) {
      //position = position || 'middle';
      //stick = stick || false;
      //time = time || 3000;
      //ionicToast.show(message, position, stick, time);
      $ionicLoading.show({ template: message, noBackdrop: true, duration: 1000 });
    }

    function alert(title, template) {
      var _alert = $ionicPopup.alert({
        title: title,
        template: template,
        'okType': 'button-assertive'
      });

      $timeout(function() {
        _alert.close(); //close the popup after 3 seconds for some reason
      }, 1500);

      return _alert;
    }

    function confirm(title, template) {
      var _alert = $ionicPopup.confirm({
        'title': title,
        'template': template,
        'okType': 'button-assertive',
        'cancelText': '取消',
        'okText': '确认',
        cssClass:'red-confirm'
      });

      $timeout(function() {
        _alert.close(); //close the popup after 3 seconds for some reason
      }, 3000);

      return _alert;
    }

    function show(title, template, scope, buttons) {
      var _alert = $ionicPopup.show({
        title: title,
        template: template,
        scope: scope,
        buttons: buttons
      });
      $timeout(function() {
        _alert.close(); //close the popup after 3 seconds for some reason
      }, 3000);

      return _alert;
    }
  }
})();
