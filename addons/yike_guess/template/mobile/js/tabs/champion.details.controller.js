
(function () {
    'use strict';

    angular
        .module('champion.details.controller', [])
        .controller('ChampionDetailCtrl', ChampionDetailCtrl);

    ChampionDetailCtrl.$inject = ['$scope','$state','$yikeUtils'];

    /* @ngInject */
    function ChampionDetailCtrl($scope,$state,$yikeUtils) {

        init();
        $scope.id=$state.params.id;
        $scope.getGuessDetails=getGuessDetails;
        $scope.add=add;
        $scope.sub=sub;
        $scope.submit=submit;
        $scope.choose=choose;
        $scope.inValue={
            num:1,
            id:0
        };
        $scope.$on('$ionicView.beforeLeave', function() {
            wx.onMenuShareAppMessage(window.shareData);
            wx.onMenuShareTimeline(window.shareData);
        });
        ////////////////

        function init() {
            getGuessDetails();
        }
        function getGuessDetails(){
            var id=$state.params.id;
            yikeGuess.getGuessDetails(id)
                .then(function(data){
                    $scope.data=data.result;
                    $scope.inValue.id=data.result.guess.contest[0].id;
                    $scope.is_open=data.result.guess.is_open;
                    if(data.result.guess.lottery){
                        $scope.lottery=data.result.guess.lottery;
                    }else{
                        $scope.lottery='';
                    }
                    //console.log($scope.data.guess);
                    var shareData = {};
                    shareData.title=data.result.guess.name;
                    shareData.imgUrl=data.result.guess.image;
                    shareData.desc=data.result.guess.describe;
                    wx.onMenuShareAppMessage(shareData);
                    wx.onMenuShareTimeline(shareData);
                    $scope.$digest();
                });
            setTimeout(function(){
                $('.zhichi').each(function(index){
                    var jifen=$(this).context.innerHTML.slice(4);
                    var red=$('.red-line')[index];
                    red.style.width=jifen;
                });

            },1000)
        }
        function add(){
            $scope.inValue.num++;
        }
        function sub(){
            if($scope.inValue.num <= 1){
                $scope.inValue.num = 1
            }else{
                $scope.inValue.num--;
            }
        }
        function submit(){
            if(isNaN(Number($scope.inValue.num))||$scope.inValue.num<1){
                $yikeUtils.toast('请输入正确的数值');
                $scope.inValue.num=1;
                return false;
            }
            $scope.data.guess.lower=Number($scope.data.guess.lower);
            $scope.inValue.num=Number($scope.inValue.num);
            if($scope.data.guess.lower>0){
                if($scope.data.guess.lower>$scope.inValue.num){
                    $yikeUtils.toast('不得低于最低下注：'+$scope.data.guess.lower);
                    return false;
                }
            }
            if($scope.data.guess.upper>0){
                if($scope.data.guess.upper<$scope.inValue.num){
                    $yikeUtils.toast('不得高于最高下注：'+$scope.data.guess.upper);
                    return false;
                }
            }
            yikeGuess.bet($scope.id,$scope.inValue.num,$scope.inValue.id)
                .then(function(data){
                    if(data.status==1){
                        $yikeUtils.toast(data.result.result);
                        init();
                    }else if(data.status==0){
                        $yikeUtils.toast(data.result.result);

                    }

                })
        }
        function choose(i){
            $scope.inValue.id=i.id;
        }

    }
})();
