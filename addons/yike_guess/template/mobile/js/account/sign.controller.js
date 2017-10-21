/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('sign.controller', [])
        .controller('SignCtrl', SignCtrl);

    SignCtrl.$inject = ['$scope','$state','$ionicPopup'];

    /* @ngInject */
    function SignCtrl($scope,$state,$ionicPopup) {

        init();
        $scope.sign=sign;
        $scope.data=[];
        $scope.myDays={
            day:''
        };
        $scope.getSignInformation=getSignInformation;
        $scope.getSignDays=getSignDays;

        ////////////////

        function init() {
            getSignInformation();
            getSignDays();
        }

        function getSignDays(){
            yikeGuess.getSignDays()
                .then(function(days){
                    console.log(days);
                    $scope.myDays.day=days.result;
                    $scope.$digest();
                })
        }

        function getSignInformation(){
            yikeGuess.getSignInformation()
                .then(function(data){
                    var r = AV._.toArray(data.result);
                    $scope.data=r;
                    console.log($scope.data);
                    $scope.$digest();
                })
        }
        function sign(){
            yikeGuess.sign()
                .then(function(data){
                    console.log(data);
                    if(data.status==1){
                        var alertPopup=$ionicPopup.alert({
                            title: '提示',
                            template: '签到成功',
                            cssClass:'reminder'
                        });
                        alertPopup.then(function(res) {
                            $state.go('tab.account');
                        });
                    }else if(data.status==0){
                        var alertPopup2=$ionicPopup.alert({
                            title: '提示',
                            template: '您今日已经签到',
                            cssClass:'reminder'
                        });
                        alertPopup2.then(function(res) {
                            return false;
                        });
                    }
                })
        }
    }
})();

