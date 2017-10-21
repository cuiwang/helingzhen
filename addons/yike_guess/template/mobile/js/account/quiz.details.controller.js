/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('quiz.details.controller', [])
        .controller('QuizDetailsCtrl', QuizDetailsCtrl);

    QuizDetailsCtrl.$inject = ['$scope','$state'];

    /* @ngInject */
    function QuizDetailsCtrl($scope,$state) {
        $scope.id=$state.params.id;
        $scope.myOrderDetails=myOrderDetails;
        init();

        ////////////////

        function init() {
            console.log($scope.id);
            myOrderDetails();
        }
        function myOrderDetails(){
            yikeGuess.myOrderDetails($scope.id)
                .then(function(data){
                    console.log(data.result);
                    $scope.data=data.result;
                    $scope.$digest();
                })
        }
    }
})();
