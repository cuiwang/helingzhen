;(function(window,$){
    function debug(str){
        if(window.console && window.console.log){
            window.console.log(str);
        }
    }
    /**
     * 抽象化表格
     */
    function abstractTable(){
        // ---------内容属性
        this.id = null;          // 每个表格都有唯一的一个id
        this.tableObj = null;   //表格对象
        this.colNum = 0;        //列数
        this.header = [];       //表头数据
        this.content = [];      //body所有数据
        this.display_tfoot = false ;  // 默认不显示tfoot的分页
        // ----------提供外部使用获得表格内部数据
        this.currentClickRowDataIndex = 0;   //当前点击的行数据
        // --- 通过表头来获得这张表的列数
        this.getColNum = function(){
            this.colNum = this.header.length;
            return  this.colNum;
        }
        // --- 得到这张表的数据行数
        this.getRowNum = function(){
            return  this.allDataNum;
        }
        // -----------  表格自我构建行为
        this.clearTable = function(){};
        this.showHeader = function(){};
        this.showContent = function(){};
        this.showFoot = function(){};

        // --------- 分页功能属性
        this.allDataNum = 0;     // 总数据条数---也就是行数
        this.displayNum = 10;    // 每页显示条数
        this.maxPageNum = 1;     // 最大页码值
        this.currentPageNum =1;  // 当前页码值
        //---------  tfoot分页组
        this.groupDataNum = 10;  //每组显示10页
        this.groupNum = 1;       //当前组

        // -------- 分页功能行为
        this.paginationFromBeginToEnd = function(begin,end){}
        this.first =  function(){}  //首页
        this.last = function(){}   //最后一页
        this.prev = function(){}   //上一页
        this.next = function(){}   //下一页
        this.goto = function(){}   //跳到某页

        //---------- 排序功能
        this.sort = false;            //默认不排序
        this.sortContent = [];        // sort 为true时开启排序功能，传递一个数组[{index:0,function(){}},{index:3,function()}{}]
        this.sortEvent = function(){} //给排序的项添加事件
        this.sortCss =function(){}    //给排序的项添加样式
        this.ascORdesc = "asc";      //升序还是降序  默认是升序asc

        //----------  搜索功能
        this.search = false;          //默认没有搜索
        // ----------- 表格初始化
        this.init = function(begin,end){}

    }

    /*
     表格对象模板
     */
    function tableTemplet(table_id){
        abstractTable.call(this);
        this.id = table_id;

    }
    /**
     * 表格对象
     * @param options
     */
    function table(options){
        if(!options){return;}
        if(!$.isPlainObject(options)){return;}
        tableTemplet.call(this,options.tableId);
        //得到表格对象
        this.tableObj = $("#"+this.id);
        //清空表格内容
        this.clearTable = function(){
            this.tableObj.html(" ");
        }
        //初始化thead
        this.showHeader = function(){
            if(this.header != null){
                var  $thead = $("<thead>"),
                    $tr = $("<tr>"),
                    $th;
                for(var i=0;i<this.colNum;i++){
                    $th = $("<th>").html(this.header[i]);
                    $th.appendTo($tr);
                }
                $tr.appendTo($thead);
                $thead.appendTo(this.tableObj);
                //添加排序功能
                if(this.sort){
                    this.sortEvent();
                    this.sortCss();
                }
            }

        }
        //初始化tbody
        this.showContent = function(){
            if(this.content != null){

                if(this.tableObj.find("tbody").length>0){this.tableObj.find("tbody").remove();}

                var $tbody = $("<tbody>"),
                    $tr,
                    $td,
                    begin = (this.currentPageNum-1)*this.displayNum,
                    end = this.currentPageNum*this.displayNum,
                    tempDaTa = this.paginationFromBeginToEnd(begin,end),
                    len = tempDaTa.length,
                    dataIndex = 0,
                    trIndex;
                // 循环创建行
                for(var i=0;i<len;i++){
                    if(tempDaTa[i]){
                        dataIndex = begin+i;
                        trIndex = i;
                        $tr = $("<tr data-tr_index="+trIndex+" data-data_index="+dataIndex+">").appendTo($tbody);
                        if(i%2==1){
                            $tr.addClass("evenrow");
                        }
                        if(options.specialRows && $.isArray(options.specialRows)){

                            for(var k = 0;k<options.specialRows.length;k++){

                                if(i==options.specialRows[k].row && options.specialRows[k]){
                                    $tr.css(options.specialRows[k].cssText);
                                }
                            }
                        }
                        // 循环创建列  取得对象中的键
                        for(var key in tempDaTa[i]){
                            if(key==="hide_data"){//隐藏值，一般用来得到表中的id，用来对单条数据进行更新或删除操作
                                continue;
                            }
                            $td = $("<td>").html(tempDaTa[i][key]).appendTo($tr);
                        }
                    }
                }
                this.tableObj.append($tbody);
                this.tbodyTRevent();
            }
        }
        //初始化tfoot
        this.showFoot = function(){
            if(this.display_tfoot){

                if(this.tableObj.find("tfoot").length>0){this.tableObj.find("tfoot").remove();}

                var $tfoot = $("<tfoot>"),
                    $tr = $("<tr>"),
                    $td = $("<td>").attr("colspan",this.colNum).addClass("paging");
                $tr.append($td);
                $tfoot.append($tr);
                this.tableObj.append($tfoot);
                this.pagination($td);
                this.paginationEvent($td);
                this.searchEvent();
            }
        }
        //表格分页初始化
        this.pagination = function(tdCell){
            var $td= typeof(tdCell) == "object" ? tdCell : $("#" + tdCell);
            //首页
            var oA = $("<a/>");
            oA.attr("href","#1");
            oA.html("首页");
            $td.append(oA);
            //上一页
            if(this.currentPageNum>=2){
                var oA = $("<a/>");
                oA.attr("href","#"+(this.currentPageNum - 1));
                oA.html("上一页");
                $td.append(oA);
            }
            //普通显示格式
            if(this.maxPageNum <= this.groupDataNum){  // 10页以内 为一组
                for(var i = 1;i <= this.maxPageNum ;i++){
                    var oA = $("<a/>");
                    oA.attr("href","#"+i);
                    if(this.currentPageNum == i){
                        oA.attr("class","current");
                    }
                    oA.html(i);
                    $td.append(oA);
                }
            }else{//超过10页以后（也就是第一组后）
                if(this.groupNum<=1){//第一组显示
                    for(var j = 1;j <= this.groupDataNum ;j++){
                        var oA = $("<a/>");
                        oA.attr("href","#"+j);
                        if(this.currentPageNum == j){
                            oA.attr("class","current");
                        }
                        oA.html(j);
                        $td.append(oA);

                    }
                }else{//第二组后面的显示
                    var begin = (this.groupDataNum*(this.groupNum-1))+ 1,
                        end ,
                        maxGroupNum = Math.ceil(this.maxPageNum/this.groupDataNum);
                    if(this.maxPageNum%this.groupDataNum!=0&&this.groupNum==maxGroupNum){
                        end = this.groupDataNum*(this.groupNum-1)+this.maxPageNum%this.groupDataNum
                    }else{
                        end = this.groupDataNum*(this.groupNum);
                    }

                    for(var j = begin;j <= end ;j++){
                        var oA = $("<a/>");
                        oA.attr("href","#"+j);
                        if(this.currentPageNum == j){
                            oA.attr("class","current");
                        }
                        oA.html(j);
                        $td.append(oA);
                    }
                }
            }
            //下一页
            if( (this.maxPageNum - this.currentPageNum) >= 1 ){
                var oA = $("<a/>");
                oA.attr("href","#" + (this.currentPageNum + 1));
                oA.html("下一页");
                $td.append(oA);
            }
            //尾页
            var oA = $("<a/>");
            oA.attr("href","#" + this.maxPageNum);
            oA.html("尾页");
            $td.append(oA);

            //提示
         /*   var oSpan = $("<span/>");
            oSpan.html(this.currentPageNum+"页/"+this.maxPageNum+"页");
            $td.append(oSpan);
*/
            if(this.search){
                //搜索文本
                var oInput = $("<input type='text' class='search-txt'/>");
                $td.append(oInput);
                //搜索btn
                var oInputBtn = $("<input type='button' value='搜索' class='search-btn'/>");
                $td.append(oInputBtn);
            }
        }
        //表格分页绑定事件
        this.paginationEvent = function(tdCell){
            var $td= typeof(tdCell) == "object" ? tdCell : $("#" + tdCell);

            var page_a = $td.find('a');
            var tempThis = this;

            page_a.unbind("click").bind("click",function(){
                var nowNum =  parseInt($(this).attr('href').substring(1));
                tempThis.goto(nowNum);
            });
        }
        // 实现分页行为
        this.paginationFromBeginToEnd= function(x,y){
            this.maxPageNum = Math.ceil(this.allDataNum/this.displayNum);
            var arrPage = [];
            for(var i= x;i<y;i++){
                arrPage.push(this.content[i]);
            }
            return arrPage;
        }
        //首页
        this.first =  function(){
            this.goto(1);
        }
        //最后一页
        this.last = function(){
            this.goto(this.maxPageNum);
        }
        //上一页
        this.prev = function(){
            var prevPageNum = this.currentPageNum - 1;
            this.goto(prevPageNum);
        }
        //下一页
        this.next = function(){
            var nextPageNum = this.currentPageNum + 1;
            this.goto(nextPageNum);
        }
        //跳到某页
        this.goto = function(toNum){
            var nowNum =  parseInt(toNum);
            var tempThis = this;
            if(nowNum>tempThis.currentPageNum){//下一组
                if(tempThis.currentPageNum%tempThis.groupDataNum==0){
                    tempThis.groupNum += 1;

                    var maxGroupNum = Math.ceil(tempThis.maxPageNum/tempThis.groupDataNum);
                    if(tempThis.groupNum>=maxGroupNum){
                        tempThis.groupNum = maxGroupNum;
                    }
                }
            }
            if(nowNum<tempThis.currentPageNum){//上一组
                if((tempThis.currentPageNum-1)%tempThis.groupDataNum==0){
                    tempThis.groupNum -= 1;
                    if(tempThis.groupNum<=1){
                        tempThis.groupNum = 1;
                    }
                }
            }
            if(nowNum>=tempThis.maxPageNum){//直接点击尾页
                var maxGroupNum = Math.ceil(tempThis.maxPageNum/tempThis.groupDataNum);
                tempThis.groupNum = maxGroupNum;
                nowNum = tempThis.maxPageNum;
            }
            if(nowNum<=1){
                tempThis.groupNum = 1;
                nowNum = 1;
            }
            tempThis.currentPageNum = nowNum;

            tempThis.showContent();
            if(this.display_tfoot){
                this.showFoot();
            }
        }
        //排序功能  给需要排序的thead>th 绑定事件
        this.sortEvent = function(){
            var tempThis = this,
                sortContent = this.sortContent;
            if( $.isArray(sortContent) && sortContent.length>0){
                $.each(sortContent,function(i,o){
                    if(o.index< tempThis.colNum){
                        tempThis.tableObj.find("thead th").eq(o.index)
                            .off("click")
                            .on("click",function(){
                                if( tempThis.ascORdesc === "asc"){
                                    if($.isFunction(o.compareCallBack)){
                                        //之前是升序 点击后变为降序
                                        tempThis.sortCss();
                                        $(this).removeClass().addClass("desc");
                                        tempThis.ascORdesc = "desc";
                                        tempThis.content.reverse();
                                    }
                                }else{
                                    if($.isFunction(o.compareCallBack)){
                                        tempThis.sortCss();
                                        $(this).removeClass().addClass("asc");
                                        tempThis.ascORdesc = "asc";
                                        tempThis.content.sort(o.compareCallBack);
                                    }
                                }
                                tempThis.showContent();
                            })
                    }
                })
            }
        }
        //给需要排序的th添加样式
        this.sortCss = function(){
            var tempThis = this,
                sortContent = this.sortContent;
            if( $.isArray(sortContent) && sortContent.length>0){
                $.each(sortContent,function(i,o){
                    if(o.index< tempThis.colNum){
                        tempThis.tableObj.find("thead th").eq(o.index)
                            .removeClass().addClass("bg");
                    }
                })
            }
        }
        //给表格body的tr绑定事件
        this.tbodyTRevent = function(){
            if(options.bindContentTr && typeof options.bindContentTr=="function" ){
                this.bindContentTr =  options.bindContentTr;
                this.bindContentTr();
            }
        }

        //前端搜索 给搜索按钮添加事件
        this.searchEvent = function(){
            var _this = this;
            function filter_data_by_search(allData,key_val){//通过搜索关键词筛选数据
                var keywords_val=key_val.toLowerCase();
                //筛选相应的数据
                var search_data=new Array();
                var all_data=allData;
                for(var row in all_data){
                    var search_flag = false;
                    for(var cell in all_data[row]){
                        if(all_data[row][cell] !== undefined){
                            var text =  all_data[row][cell].toString();
                            text = text.replace(/<br>/g,"");
                            text = text.replace(/<br\/>/g,"");
                            var index=text.toLowerCase().indexOf(keywords_val);
                            if(index != -1){
                                search_flag = true;
                            }
                        }
                    }
                    if(search_flag){
                        search_data.push(all_data[row])
                    }
                }
                return search_data;
            }        


            this.tableObj.find(".search-btn").off("click").on("click",function(){
                var allData = _this.contentForSearch,
                    key_val  = _this.tableObj.find(".search-txt").val();
                var search_data = filter_data_by_search(allData,key_val);
                _this.currentPageNum = 1 ;
                _this.allDataNum = search_data.length;
                if(_this.searchEventCallBack && $.isFunction(_this.searchEventCallBack)){
                    _this.searchEventCallBack(search_data);
                }else{
                    _this.init(search_data);
                }
            })
        }

        //初始化整个表格
        this.init = function(data){
            this.header = options.headers;
            this.colNum = this.header.length;
            this.content = data;
            this.contentForSearch = options.data;
            this.allDataNum = this.content.length;
            if(options.displayNum){
                this.displayNum = options.displayNum;
            }
            if(options.groupDataNum){
                this.groupDataNum = options.groupDataNum;
            }

            if(options.sort){
                this.sort = options.sort;
                if(options.sortContent){
                    this.sortContent = options.sortContent;
                }
            }
            if(options.display_tfoot){
                this.display_tfoot = options.display_tfoot;
                if(options.search){
                    this.search = options.search;
                    this.searchEventCallBack = options.searchEventCallBack;
                }
            }
            this.clearTable();
            this.showHeader();
            this.showContent();
            this.showFoot();
        }

        this.init(options.data);
    }
    window.table = table;

})(window,jQuery);

