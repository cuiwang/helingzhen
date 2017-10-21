/**
 * Created by yiker14 on 16/6/8.
 */
(function () {
    'use strict';

    angular
        .module('integral.detailCtrl.controller', [])
        .controller('IntegralDetailCtrl', IntegralDetailCtrl);


    //首页
    IntegralDetailCtrl.$inject = ['$scope', '$state'];

    /* @ngInject */
    function IntegralDetailCtrl($scope, $state) {

        init();
        $scope.getBalance=getBalance;

        ////////////////

        function init() {
            getBalance();
        }
        function getBalance(){
            yikeGuess.getBalance()
                .then(function(data){
                        $scope.item=data.result.list;
                        $scope.status=data.status;
                    console.log($scope.item);
                    $scope.$digest();
                })
        }
    }

})();