function _shareQQfriend(){

  var p = {
  url: 'http://www.baidu.com',/*获取URL，可加上来自分享到QQ标识，方便统计*/
  desc: '测试QQ分享', /*分享理由(风格应模拟用户对话),支持多分享语随机展现（使用|分隔）*/
  title : '测试标题',/*分享标题(可选)*/
  summary : '测试描述咯！！！！！！！！！！！！',/*分享描述(可选)*/
  pics : 'http://img32.ddimg.cn/88/16/1098937312-1_w_1.jpg',/*分享图片(可选)*/
  flash : '',	/*视频地址(可选)*/
  //commonClient : true, /*客户端嵌入标志*/
  site: 'QQ分享'/*分享来源 (可选) ，如：QQ分享*/
  };

  var s = [];
  for (var i in p) {
  s.push(i + '=' + encodeURIComponent(p[i] || ''));
  }

  window.open('http://connect.qq.com/widget/shareqq/index.html?'+s.join('&'));
}
