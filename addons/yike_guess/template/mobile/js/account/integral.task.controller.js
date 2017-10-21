/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('integral.task.controller', [])
        .controller('IntegralTaskCtrl', IntegralTaskCtrl);

    IntegralTaskCtrl.$inject = ['$scope','$state','$ionicPopup'];

    /* @ngInject */
    function IntegralTaskCtrl($scope,$state,$ionicPopup) {

        init();
        $scope.getMission=getMission;
        ////////////////

        function init() {
            getMission();
        }
        function getMission(){
            yikeGuess.getMission()
                .then(function(data){
                    var r=AV._.toArray(data.result);
                    console.log(r);
                    $scope.data=r;
                    $scope.$digest();
                })
        }

    }
})();
