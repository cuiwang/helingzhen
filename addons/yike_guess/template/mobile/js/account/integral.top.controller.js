/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('integral.top.controller', [])
        .controller('IntegralTopCtrl', IntegralTopCtrl);

    IntegralTopCtrl.$inject = ['$scope'];

    /* @ngInject */
    function IntegralTopCtrl($scope) {
        $scope.active='all';
        $scope.getMonthTop=getMonthTop;
        $scope.getAllTop=getAllTop;
        $scope.item={
            month:[],
            all:[]
        };

        init();

        ////////////////

        function init() {
            getMonthTop();
            getAllTop();
        }

        function getMonthTop(){
            yikeGuess.getMonthTop()
                .then(function(data){
                    $scope.item.month=data.result.list;
                    console.log($scope.item.month);
                    $scope.$digest();
                })
        }
        function getAllTop(){
            yikeGuess.getAllTop()
                .then(function(data){
                    $scope.item.all=data.result.list;
                    console.log($scope.item.all);
                    $scope.$digest();
                })
        }

    }
})();
