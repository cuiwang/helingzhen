/**
 * Created by leon on 8/19/15.
 */
var mainFramework = new Framework7({
    modalButtonOk : '确认',
    modalButtonCancel : '取消',
    hideNavbarOnPageScroll : false,
    modalTitle : '积分商城',//弹层标题
    pushState : true,//url
    precompileTemplates : true,//是否自动编译所有的 Template7 模板
    domCache : true,
    template7Pages: true,//如果要使用load 这里必须设置才可以生效的
    swipeBackPageAnimateShadow : false,//打开/关闭 滑动返回时候的 box-shadow 动画。关闭这个功能可以提高性能。
    swipeBackPageAnimateOpacity : false,//打开/关闭 滑动返回时候的半透明效果。关闭这个功能可以提高性能。
    //可排序列表
    sortable : false,//如果你不使用可排序列表，可以禁用这个功能。因为禁用之后可能会有潜在的性能提升。
    //滑动删除
    swipeout : false,
    swipeoutNoFollow : false
});
var $$ = Framework7.$;
var mainView = mainFramework.addView('body.main',{
        dynamicNavbar: true,
        swipePanel: 'left',
    }
);

