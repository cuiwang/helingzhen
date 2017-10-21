/**
 * Created by eva on 2016/6/2.
 */
(function () {
    'use strict';

    angular
        .module('detail.controller', [])
        .controller('DetailCtrl', DetailCtrl);

    DetailCtrl.$inject = ['$scope', '$ionicModal','$state'];

    /* @ngInject */
    function DetailCtrl($scope, $ionicModal,$state) {

        $scope.item = {
            'classify': [],
            'subClassify': [],
            'list':[],
            'title':'全部'
        };
        $scope.id=$state.params.id;
        $scope.getClassify = getClassify;
        $scope.changeMainClassify = changeMainClassify;
        $scope.changeSubClassify = changeSubClassify;
        $scope.isActive = '全部';
        $scope.subIsActive = '';
        $scope.goto=goto;
        $scope.getList=getList;
        wx.onMenuShareAppMessage(window.shareData);
        wx.onMenuShareTimeline(window.shareData);


        init();

        ////////////////

        function init() {


            $ionicModal.fromTemplateUrl('play.html', {
                scope: $scope,
                animation: 'none'
            }).then(function (method) {
                $scope.playModal = method;
            });
            $scope.openMethodModal = function () {
                $scope.playModal.show()
                    .then(function () {
                        var swiper = new Swiper(".swiper-container-main", {
                            pagination: 'null',
                            slidesPerView: 'auto',
                            scrollbarHide: false,
                            paginationClickable: true
                        });
                        setTimeout(function () {
                            swiper.update();
                        }, 2000);
                    })
            };
            $scope.closeMethodModal = function () {
                $scope.playModal.hide();
            };

            $scope.$on('$destroy', function () {
                $scope.playModal.remove();
            });

            getClassify();
            getList($scope.id);
            console.log($scope.id);

        }

        function changeMainClassify(d) {
            $scope.subIsActive = '';
            if (d) {
                $scope.isActive = d.name;
                $scope.item.title=d.name;
                getList(d.id);
                yikeGuess.getSubClassifyById(d.id)
                    .then(function (subClassify) {
                        console.log(subClassify);
                        if(subClassify){
                            $scope.item.subClassify = subClassify.result.classify;

                        }

                        $scope.$digest();
                    })
            } else {
                $scope.isActive = '全部';
                $scope.item.title= '全部';
                $scope.item.subClassify=[];
                getList();
            }
        }

        function changeSubClassify(d) {
            if (d) {
                $scope.subIsActive = d.name;
                $scope.item.title=d.name;
                getList(d.id);
            }
        }

        function getList(id){
            $scope.item.list=[];
            yikeGuess.getList(id)
                .then(function(data){
                    if(data.status==1){
                        $scope.item.list=data.result.guess;
                        AV._.each($scope.item.list,function(item,index){
                            if(item.image){
                                $scope.item.list[index].image=item.image;
                            }
                        });
                    }
                    console.log($scope.item.list);
                    $scope.$digest();
                })
        }
        function getClassify() {
            yikeGuess.getClassify()
                .then(function (classifys) {
                    $scope.item.classify = classifys.result.classify_main;
                    console.log($scope.item.classify);
                    if($scope.id){
                        AV._.each($scope.item.classify,function(classify){
                            if($scope.id==classify.id){
                                $scope.item.title=classify.name;
                                $scope.isActive=classify.name;
                            }
                        })
                    }
                    $scope.$digest();
                })
        }

        function goto(d){
            console.log(d);
            if(d.play_id==2){
                $state.go('champion-details',{id: d.id});
            }else{
                $state.go('details',{id: d.id});
            }
        }
    }
})();