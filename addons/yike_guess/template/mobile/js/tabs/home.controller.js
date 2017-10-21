/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('home.controller', [])
        .controller('HomeCtrl', HomeCtrl);


    //首页
    HomeCtrl.$inject = ['$scope', '$state','$ionicSlideBoxDelegate','$ionicPopup','$yikeUtils','$ionicScrollDelegate'];

    /* @ngInject */
    function HomeCtrl($scope, $state,$ionicSlideBoxDelegate,$ionicPopup,$yikeUtils,$ionicScrollDelegate) {
        $scope.home = {
            'classifys': classify,
            'banner': [],
            'guess': guess,
            'follow':follow
        };
        $scope.isFollow=isFollow;
        $scope.goto=goto;
        wx.onMenuShareAppMessage(window.shareData);
        wx.onMenuShareTimeline(window.shareData);
        $scope.scrollTop = scrollTop;

        init();

        ////////////////

        function init() {

            $("#xd").lazyload({
                    threshold : 200,
                    placeholder:'./img/load.jpg',
                    effect : "fadeIn",
                }
            );

            AV._.each($scope.home.guess,function(d,index){
                $scope.home.guess[index].image=d.image;
            });

            AV._.each($scope.home.classifys,function(classify,index){
                if(classify.image){
                    $scope.home.classifys[index].image=classify.image;
                }
            });

            setTimeout(function(){
                AV._.each(banner,function(d,index){
                    $scope.home.banner.push(d);
                });

                $scope.$digest();
                $ionicSlideBoxDelegate.update();
                $ionicSlideBoxDelegate.loop(true);
            },100);

            if($scope.home.follow.follow==0){
                var alertPopup1=$ionicPopup.alert({
                    title: '提示',
                    template: '请先关注',
                    okType:'button-assertive',
                });
                alertPopup1.then(function(res) {
                    location.href=$scope.home.follow.href;
                });
            }
            console.log($scope.home);
            isFollow();

        }

        function query(page){
            $yikeUtils.toast('查询中..');
        }


        function isFollow(){
            yikeGuess.isFollow()
                .then(function(data){
                    console.log(data);
                })
        }
        function goto(d){
            if(d.play_id==2){
                $state.go('champion-details',{id: d.id});
            }else{
                $state.go('details',{id: d.id});
            }
        }

        //返回顶部
        function scrollTop() {
            $ionicScrollDelegate.scrollTop();
        }

        $scope.getScrollPosition = function () {
            //monitor the scroll
            $scope.moveData = $ionicScrollDelegate.getScrollPosition().top;

            if ($scope.moveData >= 250) {
                $('.scrollToTop').fadeIn();
            } else if ($scope.moveData < 250) {
                $('.scrollToTop').fadeOut();
            }
        };
    }

})();
