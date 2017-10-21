<script id="home-memnu" type="text/html">
  <div class="">
      <div class="swiper-container qsc-tab tab-fixed home-navbar swiper-container-horizontal swiper-container-free-mode " >
          <ul class="swiper-wrapper tab-item">
            {{each list as row i}}
            <li class="swiper-slide {{if i==0'}} active {{/if}}  swiper-slide-active">
                <a href="1">{{row.project_name}}</a>
            </li>
            {{/each}}
          </ul>
      </div>
  </div>
</script>
