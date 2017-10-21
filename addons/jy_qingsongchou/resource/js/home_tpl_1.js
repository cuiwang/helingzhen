{{each list as value i}}

 <div class="mod-project-card card-type-1">
     <a href="">
         <div class="mod-project-card_header clearfix">
             <div class="mod-project-card_header__avatar">
                 <img class="lazy-load" src="" data-original="{{value.avatar}}" width="24" height="24" alt="">
             </div>
             <div class="mod-project-card_header__user">
                 <span>{{value.nickname}}</span>
                 <em class="mod-project-card_header__time">{{value._time}}</em>
             </div>
         </div>
         <div class="mod-project-card_content">
             <header class="mod-project-card_content__header">
                 <h2>{{value.title}}</h2>
             </header>
             <div class="mod-project-card_content__detail">
                 <p class="description">
                    {{value.desc}}
                 </p>

                   <div class="mod-project-card_content__img clearfix">
                     {{each value.thumb as value i}}

                     <img class="lazy-load" data-original="{{value}}" width="25%" alt="{{title}}" style="display: block;">

                      {{/each}}
                   </div>

             </div>
             <div class="mod-project-card_content__supporter clearfix">


                   <strong>

                        {{each value._thumb as value i}}
                     <span>
                       <img class="lazy-load"  data-original="{{value}}" width="24" style="display: inline;">
                     </span>
                     {{/each}}
                   </strong>

                 <span>已有 <strong>{{value.suport}}</strong> 人支持</span>
             </div>
             <div class="mod-project-card_content__status clearfix">
                 <span><i class="icon icon-aims"></i>目标<strong>{{value.target}}</strong>元</span>
                 <span><i class="icon icon-already"></i>已筹<strong>{{value._com}}</strong>元</span>
                 <span><i class="icon icon-supporter"></i>剩余<strong>{{value.lastDay}}</strong>天</span>

             </div>
         </div>
     </a>
 </div>
   {{/each}}
