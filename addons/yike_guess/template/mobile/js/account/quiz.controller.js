/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('quiz.controller', [])
        .controller('QuizCtrl', QuizCtrl);

    QuizCtrl.$inject = ['$scope'];

    /* @ngInject */
    function QuizCtrl($scope) {
        $scope.quizStatus='not';
        $scope.myOrder=myOrder;
        $scope.myOpenOrder=myOpenOrder;
        $scope.order={
            ing:'',
            open:''
        };
        init();

        ////////////////

        function init() {
            myOrder();
            myOpenOrder();
        }
        function myOrder(){
            yikeGuess.myOrder()
                .then(function(item){
                    $scope.order.ing=item.result.order;
                    console.log($scope.order.ing);
                    $scope.$digest();
                })
        }
        function myOpenOrder(){
            yikeGuess.myOpenOrder()
                .then(function(data){
                    $scope.order.open=data.result.order;
                    console.log($scope.order.open);
                    $scope.$digest();
                })
        }
    }
})();
