/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('account.controller', [])
        .controller('AccountCtrl', AccountCtrl);

    AccountCtrl.$inject = ['$scope'];

    /* @ngInject */
    function AccountCtrl($scope) {

        init();
        $scope.getUser=getUser;
        $scope.moduleIsOpen=moduleIsOpen;

        ////////////////

        function init() {
            getUser();
            moduleIsOpen();
        }
        function getUser(){
            yikeGuess.getUser()
                .then(function(user){
                    $scope.user=user.result.user;
                    console.log($scope.user);
                    $scope.$digest();
                })
        }
        function moduleIsOpen(){
            yikeGuess.moduleIsOpen()
                .then(function(data){
                    $scope.data=data.result.on_off;
                    console.log($scope.data);
                    $scope.$digest();
                })
        }
    }
})();
