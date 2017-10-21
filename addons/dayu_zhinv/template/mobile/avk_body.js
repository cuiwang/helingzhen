var PLAYSOUND=false;
var PAUSED=false;

function pauseGame() 
{
    if (PLAYSOUND)
    {
        GLOB_in_menu.stop();
        PAUSED=true;
    }
}

function resumeGame() 
{
    if ((PLAYSOUND)&&(PAUSED))
    {
        GLOB_in_menu.play("none",0,0,-1);
    }
    PAUSED=false;
}

function avk_start()
{
    Visibility.change(function (e, state) 
    {
        if (state=="hidden")
            pauseGame();
        else if (PAUSED)
        resumeGame();
    });

 

    var RANDOM_EMITTER=10;
    var EMITTER_RATE=1000;
    var DYM_LIFE=3500;
    var SpilData = {id: '576742227280292394'};
    
    function gl_vars()
    {
        this.RUN=false;
                
        this.INTRO_SHOWED=false;
        this.AFTER_INTRO=false;
        this.STEPS=0;
        this.STEP1=0;
        this.STEP2=0;
        this.STEP3=0;
        this.SHOW_HANDLER=null;
        this.WND_TITLE=null;
        this.WND_GAME=null;
        this.WND_LEVELS=null;
        this.CURRENT_LEVEL=0;
        this.EL_WIDTH=0;
        this.EL_HEIGHT=0;
        this.ELEMENT=null;
        this.MAP_X=0;
        this.MAP_Y=0;
        this.MAP_WIDTH=0;
        this.MAP_HEIGHT=0;
        this.START_X=-1;
        this.START_Y=0;
        this.MAX_ARRAY=5;
        this.SUFIX="";
        this.MOVE_TICK=200;
        this.EDIT_X=0;
        this.EDIT_Y=0;
        this.PROG=0;
        this.SKACHKA=false;
        this.PLAY_SND=true;
        this.WND_EMITTERS;
        this.PRINCESS_PAUSE=0;
        this.PATH=new Array(2*this.MAX_ARRAY*this.MAX_ARRAY);
        this.PROGRESS=[-1,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2,-2];
        this.CLOUDS=[];
        this.API=0;
        this.LINK=0;

        this.LEVELS=[   /*{sufix:"_2",map_name:"game_place",map_width:2,map_height:2,start_x:0,start_y:-1,finish_x:2,finish_y:1,blocks:[{mv:true,ch:0,id:5},{mv:true,ch:0,id:10}, {mv:true,ch:0,id:-1},{mv:true,ch:0,id:3}]},
                        {sufix:"_3",map_name:"game_place",map_width:3,map_height:3,start_x:-1,start_y:1,finish_x:3,finish_y:2,blocks:[{mv:true,ch:0,id:10},{mv:true,ch:0,id:9},{mv:true,ch:0,id:-1}, {mv:true,ch:0,id:6},{mv:true,ch:0,id:12},{mv:true,ch:0,id:-1}, {mv:true,ch:0,id:11},{mv:true,ch:0,id:15},{mv:true,ch:0,id:13}]},
                        {sufix:"_3",map_name:"game_place",map_width:3,map_height:3,start_x:1,start_y:-1,finish_x:2,finish_y:3,blocks:[{mv:true,ch:0,id:-1},{mv:true,ch:0,id:-1},{mv:true,ch:0,id:-1}, {mv:true,ch:4,id:5},{mv:true,ch:2,id:6},{mv:true,ch:8,id:9}, {mv:true,ch:1,id:3},{mv:true,ch:0,id:10},{mv:true,ch:0,id:12}]},
                        {sufix:"_3",map_name:"game_place",map_width:3,map_height:3,start_x:1,start_y:-1,finish_x:3,finish_y:2,blocks:[{mv:true,ch:0,id:10},{mv:true,ch:0,id:9},{mv:true,ch:0,id:15}, {mv:true,ch:0,id:-1},{mv:true,ch:0,id:12},{mv:true,ch:0,id:-1}, {mv:true,ch:0,id:11},{mv:false,ch:0,id:15},{mv:true,ch:0,id:13}]},
                        {sufix:"_4",map_name:"game_place",map_width:4,map_height:4,start_x:-1,start_y:1,finish_x:4,finish_y:3,blocks:[{mv:true,ch:0,id:10},{mv:true,ch:0,id:12},{mv:true,ch:0,id:-1},{mv:true,ch:0,id:5}, {mv:true,ch:0,id:0},{mv:true,ch:0,id:10},{mv:true,ch:0,id:6},{mv:true,ch:0,id:9}, {mv:true,ch:0,id:-1},{mv:true,ch:0,id:-1},{mv:true,ch:0,id:3},{mv:true,ch:4,id:6}, {mv:true,ch:0,id:-1},{mv:true,ch:0,id:-1},{mv:true,ch:2,id:10},{mv:true,ch:9,id:9}]}*/
                    ];

        this.LOG=function(txt)
        {
            game.MAIN.txt_log.set_text(game.MAIN.txt_log.caption+"\n"+txt);
            game.MAIN.txt_log.sprite.position.y=game.MAIN.txt_log.y+(game.MAIN.log_place.uni_height-game.MAIN.txt_log.txt.textHeight);
            game.MAIN.txt_log.txt.position.y=0;
        }

        var tk=(new Date()).getTime();
        this.FUNC=function(txt)
        {
            var tm = (new Date()).getTime();

            game.MAIN.txt_log.set_text(game.MAIN.txt_log.caption+"\n"+txt+"["+(tm-tk)+"]");
            tk=tm;
            game.MAIN.txt_log.sprite.position.y=game.MAIN.txt_log.y+(game.MAIN.log_place.uni_height-game.MAIN.txt_log.txt.textHeight);
            game.MAIN.txt_log.txt.position.y=0;
        }
    }

    var GLOBAL=new gl_vars();


    function APIready(apiInstance) 
    {
        GLOBAL.API=apiInstance;
    }

    GameAPI.loadAPI (APIready,SpilData);

    var that = this;
    var order = [];
    var to_update = [];
    var game = new AVK_GAME(init,update,event);

    var actions={   to_left:{pause:0,time:350,changes:{global_x:{beg:0,end:-1,trans:"sqrt"}}},
                    from_right:{pause:0,time:350,changes:{global_x:{beg:1,end:0,trans:"sqrt"}}},
                    to_right:{pause:0,time:350,changes:{global_x:{beg:0,end:1,trans:"sqrt"}}},
                    from_left:{pause:0,time:350,changes:{global_x:{beg:-1,end:0,trans:"sqrt"}}},
                    scale:{pause:0,time:150,changes:{prop:{beg:0,end:Math.PI,trans:"sin"}}},
                    ui_scale:{pause:0,time:1000,changes:{prop:{beg:0,end:Math.PI*2,trans:"sin"}}},
                    scale_life:{pause:0,time:250,changes:{scale:{beg:0,end:1,trans:"sqrt"}}},
                    tryaska:{pause:0,time:500,changes:{prop:{beg:0,end:1,trans:"lin"}}},
                    alpha:{pause:0,time:1000,changes:{alpha:{beg:0,end:1,trans:"lin"}}},
                    finish_way:{pause:0,time:400,changes:{prop:{beg:0,end:1,trans:"lin"}}},
                    show_zw:{pause:400,time:1000,changes:{prop:{beg:0,end:1,trans:"lin"}}},
                    show_intro:{pause:300,time:7000,changes:{prop:{beg:0,end:11,trans:"lin"}}},
                    tutor:{pause:1000,time:700,changes:{prop:{beg:0,end:1,trans:"n2"}}},
                    pr:{pause:0,time:350,changes:{prop:{beg:0,end:1,trans:"sqrt"}}}
                };

    function save()
    {
        var stars, level = GLOBAL.CURRENT_LEVEL, steps = GLOBAL.STEPS;

        if(steps < GLOBAL['STEP' + 1]){
            stars = 3;
        } else if(steps < GLOBAL['STEP' + 2]){
            stars = 2;
        } else {
            stars = 1;
        }
		myStorage.PlayerWin({
            level: 1 + GLOBAL.CURRENT_LEVEL,
            stars: stars,
            steps: steps,
            success: 1
        });
        try
        {
            myStorage.Storage.setItem('avk_tew_data', GLOBAL.PROGRESS);
        } catch(e) {};

        load();
    }

    function load()
    {
        try
        {
            if (myStorage.Storage.getItem('avk_tew_data') == null)
            {
                myStorage.Storage.setItem('avk_tew_data', GLOBAL.PROGRESS);
            }

            var n=0;
            var t=0;
            var s=myStorage.Storage.getItem('avk_tew_data')+",";
            for (var i=0;i<s.length;i++)
            {
                if(s[i]==",")
                {
                    GLOBAL.PROGRESS[t]=(s.substring(n,i)/1);
                    n=i+1;
                    t++;
                }
            }
        } catch(e) {};

        for (i=1;i<21;i++)
        {
            game.LEVELS["btn_lev_"+i].sprite.visible=(GLOBAL.PROGRESS[i-1]>-2);
            game.LEVELS["z_"+i+"_1"].sprite.visible=(GLOBAL.PROGRESS[i-1]>0);
            game.LEVELS["z_"+i+"_2"].sprite.visible=(GLOBAL.PROGRESS[i-1]>1);
            game.LEVELS["z_"+i+"_3"].sprite.visible=(GLOBAL.PROGRESS[i-1]>2); 
            game.LEVELS["zz_"+i+"_1"].sprite.visible=(GLOBAL.PROGRESS[i-1]>0);
            game.LEVELS["zz_"+i+"_2"].sprite.visible=(GLOBAL.PROGRESS[i-1]>1);
            game.LEVELS["zz_"+i+"_3"].sprite.visible=(GLOBAL.PROGRESS[i-1]>2);

            game.LEVELS["lock_"+i].sprite.visible=!game.LEVELS["btn_lev_"+i].sprite.visible;

            if (game.LEVELS["btn_lev_"+i].sprite.visible)
                GLOBAL.WND_TITLE.level_btn=game.LEVELS["btn_lev_"+i];
        }
    }

    function show_wnd_right(wnd) 
    {
        game.GUI_BUSY=true;
        order.push(wnd);
        wnd.sprite.visible = true;
        wnd.sprite.position.x=game.SCREEN_WIDTH;
        game.ACT.start("from_left",wnd);
        game.ACT.start("to_right",order[order.length-2],hide_old);
    }

    function show_wnd_left(wnd) 
    {
        game.GUI_BUSY=true;
        order.push(wnd);
        wnd.sprite.visible = true;
        wnd.sprite.position.x=game.SCREEN_WIDTH;
        game.ACT.start("from_right",wnd,on_show);
        game.ACT.start("to_left",order[order.length-2],hide_old);
    }

    function hide_wnd_right() 
    {
        game.GUI_BUSY=true;
        order[order.length-2].sprite.visible = true;
        game.ACT.start("from_right",order[order.length-2]);
        game.ACT.start("to_left",order[order.length-1],hide_old);
        order.pop();
    }

    function hide_wnd_left()
    {
        game.GUI_BUSY=true;
        order[order.length-2].sprite.visible = true;
        game.ACT.start("from_left",order[order.length-2]);
        game.ACT.start("to_right",order[order.length-1],hide_old);
        order.pop();
    }

    function on_show()
    {
        if (GLOBAL.SHOW_HANDLER!=null)
        {
            GLOBAL.SHOW_HANDLER();
            GLOBAL.SHOW_HANDLER=null;
        }
    }

    function hide_old(wnd) 
    {
        game.GUI_BUSY=false;
        PAUSE=false;
        wnd.sprite.visible = false;
    }

    function convert(value)
    {
        var s = "";
        var t = 0;
        value=""+value;
        
        for (var i = value.length - 1; i >= 0; i--)
        {
            if (t == 3)
            {
                t = 0;
                s = " " + s;
            }
            
            if ((value.charAt(i) != "0") && (value.charAt(i) != "1") && (value.charAt(i) != "2") && (value.charAt(i) != "3") && (value.charAt(i) != "4") && (value.charAt(i) != "5") && (value.charAt(i) != "6") && (value.charAt(i) != "7") && (value.charAt(i) != "8") && (value.charAt(i) != "9"))
                return value;
                
            s = value.charAt(i) + s;
            t++;
        }
        
        return s;
    }

    function AVK_CONTAINER()
    {
        var maked_objects={};
        var here=this;

        this.init=function(obj,place)
        {
            obj.active=1;
            obj.sprite.visible=true;

            if (place!=null)
                place.add(obj);

            return obj;
        }

        this.free=function(obj)
        {
            obj.active=0;
            obj.sprite.visible=false;

            if (obj.sprite.parent!=null)
                obj.sprite.parent.removeChild(obj.sprite);

            return obj;
        }

        this.get_object=function(owner,prototype,place)
        {
            if (maked_objects[owner+"_"+prototype]==null)
                maked_objects[owner+"_"+prototype]=[];

            for (var i=0;i<maked_objects[owner+"_"+prototype].length;i++)
                if (maked_objects[owner+"_"+prototype][i].active<=0)
                    return here.init(maked_objects[owner+"_"+prototype][i],place);

            if (maked_objects[owner+"_"+prototype].length==0)
            {
                game[owner][prototype].ID=0;
                maked_objects[owner+"_"+prototype].push(game[owner][prototype]);
                return here.init(game[owner][prototype],place);
            }else
            {
                maked_objects[owner+"_"+prototype].push(maked_objects[owner+"_"+prototype][maked_objects[owner+"_"+prototype].length-1].make_copy());
                return here.init(maked_objects[owner+"_"+prototype][maked_objects[owner+"_"+prototype].length-1],place);
            }
        }
    }
    var CONTAINER=new AVK_CONTAINER();

    function is_snd() 
    {
        PLAYSOUND=game.GAME.btn_snd.sprite.visible;
        return game.GAME.btn_snd.sprite.visible;
    }

    function change_snd() 
    {
        if (GLOB_M)
        {
            GLOBAL.PLAY_SND=!GLOBAL.PLAY_SND;
        }else
        {
            GLOBAL.PLAY_SND=false;
        }

        game.GAME.btn_snd.sprite.visible=GLOBAL.PLAY_SND;
        game.GAME.btn_no_snd.sprite.visible=!GLOBAL.PLAY_SND;
        game.GAME.btn_snd.down_sprite.visible=false;
        game.GAME.btn_no_snd.down_sprite.visible=false;

        if (game.GAME.btn_snd.sprite.visible)
        {
            game.GAME.sprite.addChild(game.GAME.btn_snd.sprite);
            game.GAME.sprite.addChild(game.GAME.btn_snd.down_sprite);

            game.GAME.sprite.removeChild(game.GAME.btn_no_snd.sprite);
            game.GAME.sprite.removeChild(game.GAME.btn_no_snd.down_sprite);
            
        }else
        {
            game.GAME.sprite.removeChild(game.GAME.btn_snd.sprite);
            game.GAME.sprite.removeChild(game.GAME.btn_snd.down_sprite);

            game.GAME.sprite.addChild(game.GAME.btn_no_snd.sprite);
            game.GAME.sprite.addChild(game.GAME.btn_no_snd.down_sprite);
        }

        game.MAIN.btn_snd.sprite.visible=GLOBAL.PLAY_SND;
        game.MAIN.btn_no_snd.sprite.visible=!GLOBAL.PLAY_SND;
        game.MAIN.btn_snd.down_sprite.visible=false;
        game.MAIN.btn_no_snd.down_sprite.visible=false;
        
        if (game.MAIN.btn_snd.sprite.visible)
        {
            game.MAIN.sprite.addChild(game.MAIN.btn_snd.sprite);
            game.MAIN.sprite.addChild(game.MAIN.btn_snd.down_sprite);

            game.MAIN.sprite.removeChild(game.MAIN.btn_no_snd.sprite);
            game.MAIN.sprite.removeChild(game.MAIN.btn_no_snd.down_sprite);
            
        }else
        {
            game.MAIN.sprite.removeChild(game.MAIN.btn_snd.sprite);
            game.MAIN.sprite.removeChild(game.MAIN.btn_snd.down_sprite);

            game.MAIN.sprite.addChild(game.MAIN.btn_no_snd.sprite);
            game.MAIN.sprite.addChild(game.MAIN.btn_no_snd.down_sprite);
        }

        game.CREDITS.btn_snd.sprite.visible=GLOBAL.PLAY_SND;
        game.CREDITS.btn_no_snd.sprite.visible=!GLOBAL.PLAY_SND;
        game.CREDITS.btn_snd.down_sprite.visible=false;
        game.CREDITS.btn_no_snd.down_sprite.visible=false;

        if (game.CREDITS.btn_snd.sprite.visible)
        {
            game.CREDITS.sprite.addChild(game.CREDITS.btn_snd.sprite);
            game.CREDITS.sprite.addChild(game.CREDITS.btn_snd.down_sprite);

            game.CREDITS.sprite.removeChild(game.CREDITS.btn_no_snd.sprite);
            game.CREDITS.sprite.removeChild(game.CREDITS.btn_no_snd.down_sprite);
            
        }else
        {
            game.CREDITS.sprite.removeChild(game.CREDITS.btn_snd.sprite);
            game.CREDITS.sprite.removeChild(game.CREDITS.btn_snd.down_sprite);

            game.CREDITS.sprite.addChild(game.CREDITS.btn_no_snd.sprite);
            game.CREDITS.sprite.addChild(game.CREDITS.btn_no_snd.down_sprite);
        }

        game.LEVELS.btn_snd.sprite.visible=GLOBAL.PLAY_SND;
        game.LEVELS.btn_no_snd.sprite.visible=!GLOBAL.PLAY_SND;
        game.LEVELS.btn_snd.down_sprite.visible=false;
        game.LEVELS.btn_no_snd.down_sprite.visible=false;

        if (game.LEVELS.btn_snd.sprite.visible)
        {
            game.LEVELS.sprite.addChild(game.LEVELS.btn_snd.sprite);
            game.LEVELS.sprite.addChild(game.LEVELS.btn_snd.down_sprite);

            game.LEVELS.sprite.removeChild(game.LEVELS.btn_no_snd.sprite);
            game.LEVELS.sprite.removeChild(game.LEVELS.btn_no_snd.down_sprite);
            
        }else
        {
            game.LEVELS.sprite.removeChild(game.LEVELS.btn_snd.sprite);
            game.LEVELS.sprite.removeChild(game.LEVELS.btn_snd.down_sprite);

            game.LEVELS.sprite.addChild(game.LEVELS.btn_no_snd.sprite);
            game.LEVELS.sprite.addChild(game.LEVELS.btn_no_snd.down_sprite);
        }

        game.INTRO.btn_snd.sprite.visible=GLOBAL.PLAY_SND;
        game.INTRO.btn_no_snd.sprite.visible=!GLOBAL.PLAY_SND;
        game.INTRO.btn_snd.down_sprite.visible=false;
        game.INTRO.btn_no_snd.down_sprite.visible=false;

        if (game.INTRO.btn_snd.sprite.visible)
        {
            game.INTRO.sprite.addChild(game.INTRO.btn_snd.sprite);
            game.INTRO.sprite.addChild(game.INTRO.btn_snd.down_sprite);

            game.INTRO.sprite.removeChild(game.INTRO.btn_no_snd.sprite);
            game.INTRO.sprite.removeChild(game.INTRO.btn_no_snd.down_sprite);
            
        }else
        {
            game.INTRO.sprite.removeChild(game.INTRO.btn_snd.sprite);
            game.INTRO.sprite.removeChild(game.INTRO.btn_snd.down_sprite);

            game.INTRO.sprite.addChild(game.INTRO.btn_no_snd.sprite);
            game.INTRO.sprite.addChild(game.INTRO.btn_no_snd.down_sprite);
        }

        GLOB_in_menu.stop();
            
        if (is_snd())
            GLOB_in_menu.play("none",0,0,-1);
    }

    function init() 
    {
        if (GLOB_M)
        {
            if (GLOB_in_menu==0)
                GLOB_M=false;
        }

        game.ACT.init(actions);

        game.LANGUAGE="TXT";
        game.init_captions();

        GLOBAL.WND_TITLE = new AVK_WND_TITLE();
        GLOBAL.WND_GAME = new AVK_WND_GAME();
        GLOBAL.WND_LEVELS = new AVK_WND_LEVELS();
        GLOBAL.WND_CREDITS = new AVK_WND_CREDITS();
        CONTAINER.free(CONTAINER.get_object("GAME","block_3",game.GAME.game_place));
        game.GAME.start_3.sprite.visible=false;
        game.GAME.finish_3.sprite.visible=false;
        
        game.INTRO.up.sprite.scale.x=game.SCREEN_WIDTH/game.INTRO.up.uni_width*1.2;
        game.INTRO.up.sprite.position.x=-5;
        game.GAME.wrays.sprite.scale.x=5;
        game.GAME.wrays.sprite.scale.y=5;
        game.GAME.wrays.sprite.anchor.x=0.5;
        game.GAME.wrays.sprite.anchor.y=0.5;
        game.GAME.wrays.sprite.position.x+=game.GAME.wrays.uni_width/2;
        game.GAME.wrays.sprite.position.y+=game.GAME.wrays.uni_height/2;

        game.GAME.win.y=game.GAME.win.sprite.position.y;

        game.INTRO.btn_start.add_active(game.INTRO.start_btn.sprite);
        game.INTRO.btn_snd.add_active(game.INTRO.snd_btn.sprite);
        game.INTRO.btn_no_snd.add_active(game.INTRO.no_snd_btn.sprite);
        game.INTRO.btn_no_snd.sprite.visible=false;

        game.MAIN.btn_start.add_active(game.MAIN.start_btn.sprite);
        //game.MAIN.btn_more.add_active(game.MAIN.more_btn.sprite);
        //game.MAIN.btn_credits.add_active(game.MAIN.credits_btn.sprite);
        game.MAIN.btn_snd.add_active(game.MAIN.snd_btn.sprite);
        game.MAIN.btn_no_snd.add_active(game.MAIN.no_snd_btn.sprite);
        game.MAIN.btn_no_snd.sprite.visible=false;

        game.GAME.btn_next_win.add_active(game.GAME.next_btn_win.sprite);
        //game.GAME.btn_more.add_active(game.GAME.more_btn.sprite);
        game.GAME.btn_restart.add_active(game.GAME.restart_btn.sprite);
        game.GAME.btn_close.add_active(game.GAME.close_btn.sprite);
        game.GAME.btn_close_win.add_active(game.GAME.close_btn_win.sprite);
        game.GAME.btn_new_close_win.add_active(game.GAME.new_close_btn_win.sprite);
        game.GAME.btn_edit.sprite.visible=false;

        game.TITLE.btn_start.add_active(game.TITLE.start_btn.sprite);

        game.CREDITS.btn_start.add_active(game.CREDITS.start_btn.sprite);
        game.CREDITS.btn_snd.add_active(game.CREDITS.snd_btn.sprite);
        game.CREDITS.btn_no_snd.add_active(game.CREDITS.no_snd_btn.sprite);
        game.CREDITS.btn_no_snd.sprite.visible=false;

        for (var i=1;i<21;i++)
        {
            game.LEVELS["btn_lev_"+i].add_active(game.LEVELS["lev_btn_"+i].sprite);
            game.LEVELS["btn_lev_"+i].center();
        }
                
        game.LEVELS.btn_close.add_active(game.LEVELS.close_btn.sprite);
        game.LEVELS.btn_snd.add_active(game.LEVELS.snd_btn.sprite);
        game.LEVELS.btn_no_snd.add_active(game.LEVELS.no_snd_btn.sprite);
        game.LEVELS.btn_no_snd.sprite.visible=false;

        game.GAME.btn_snd.add_active(game.GAME.snd_btn.sprite);
        game.GAME.btn_no_snd.add_active(game.GAME.no_snd_btn.sprite);
        game.GAME.btn_no_snd.sprite.visible=false;
        game.GAME.txt_level.set_style(1,"AVK_FNT_main","center");
        game.GAME.txt_moves.set_style(1.2,"AVK_FNT_main","center");
        game.GAME.txt_step_1.set_style(1.5,"AVK_FNT_main","center");
        game.GAME.txt_step_2.set_style(1.5,"AVK_FNT_main","center");
        game.GAME.txt_step_3.set_style(1.5,"AVK_FNT_main","center");

        game.GAME.back.sprite.interactive = true;
        game.GAME.back.sprite.mousemove = game.GAME.back.sprite.touchmove = game.GAME.back.sprite.touch = function(data)
        {
            if (!GLOBAL.RUN)
                return;

            event("move","GLOBAL","GLOBAL",data.global.x/game.GUI.scale.x,(data.global.y-game.GUI.position.y)/game.GUI.scale.y);
        }

        game.GAME.back.sprite.mouseout = game.GAME.back.sprite.mouseupoutside = game.GAME.back.sprite.mouseclick = game.GAME.back.sprite.mouseup = game.GAME.back.sprite.touchend = game.GAME.back.spritemouseupoutside = game.GAME.back.sprite.touchendoutside = function(data)
        {
            if (!GLOBAL.RUN)
                return;

            event("finish","GLOBAL","GLOBAL",data.global.x/game.GUI.scale.x,(data.global.y-game.GUI.position.y)/game.GUI.scale.y);
            
        }

        game.GAME.back.sprite.mousedown = game.GAME.back.sprite.touchstart = function(data)
        {
            if (!GLOBAL.RUN)
                return;

            event("start","GLOBAL","GLOBAL",data.global.x/game.GUI.scale.x,(data.global.y-game.GUI.position.y)/game.GUI.scale.y);
        }

        game.GAME.princess_place.add(game.PRINCESS.prnc);
        game.GAME.hero_place.add(game.HERO_S.stay);
        game.HERO_S.go.sprite.visible=false;
        game.HERO_S.stay.time=6000;
        game.HERO_S.go.time=200;
        game.BACK_SPR.addChild(game.MAIN.back.sprite);

        GLOBAL.CLOUDS.push(game.MAIN.cl_0.sprite);
        game.BACK_SPR.addChild(GLOBAL.CLOUDS[GLOBAL.CLOUDS.length-1]);
        GLOBAL.CLOUDS.push(game.MAIN.cl_0.make_copy().sprite);
        game.BACK_SPR.addChild(GLOBAL.CLOUDS[GLOBAL.CLOUDS.length-1]);
        GLOBAL.CLOUDS.push(game.MAIN.cl_1.sprite);
        game.BACK_SPR.addChild(GLOBAL.CLOUDS[GLOBAL.CLOUDS.length-1]);
        GLOBAL.CLOUDS.push(game.MAIN.cl_1.make_copy().sprite);
        game.BACK_SPR.addChild(GLOBAL.CLOUDS[GLOBAL.CLOUDS.length-1]);
        GLOBAL.CLOUDS.push(game.MAIN.cl_2.sprite);
        game.BACK_SPR.addChild(GLOBAL.CLOUDS[GLOBAL.CLOUDS.length-1]);

        GLOBAL.WND_EMITTERS=new AVK_WND_EMITTERS();
        //GLOBAL.CLOUDS.push(game.MAIN.cl_2.make_copy().sprite);
        //game.BACK_SPR.addChild(GLOBAL.CLOUDS[GLOBAL.CLOUDS.length-1]);

        for (i=0;i<GLOBAL.CLOUDS.length;i++)
        {
            GLOBAL.CLOUDS[i].position.x=Math.random()*game.SCREEN_WIDTH*2-game.SCREEN_WIDTH;
            GLOBAL.CLOUDS[i].position.y=Math.random()*game.SCREEN_HEIGHT/2-game.SCREEN_HEIGHT*0.2;
            GLOBAL.CLOUDS[i].speed=Math.random()*30+30;
        }

        game.LEVELS.btn_add.sprite.visible=false;
        load();
        game.GAME.sled.sprite.visible=false;
        
        game.GAME.zv1.y=game.GAME.zv1.sprite.position.y;
        game.GAME.zv2.y=game.GAME.zv2.sprite.position.y;
        game.GAME.zv3.y=game.GAME.zv3.sprite.position.y;
        game.GAME.win.x=game.GAME.win.sprite.position.x;
        game.GAME.win.y=game.GAME.win.sprite.position.y;

        /*var im1 = new PIXI.Graphics();
        im1.clear();
        im1.beginFill(0,1);
        im1.drawRect(0,0,game.INTRO.back_1_into.uni_width,game.INTRO.back_1_into.uni_height);
        im1.endFill();
        game.INTRO.back_1_into.sprite.addChild(im1);
        game.INTRO.back_1_into.sprite.mask=im1;

        var im2 = new PIXI.Graphics();
        im2.clear();
        im2.beginFill(0,1);
        im2.drawRect(0,0,game.INTRO.back_2_into.uni_width,game.INTRO.back_2_into.uni_height);
        im2.endFill();
        game.INTRO.back_2_into.sprite.addChild(im2);
        game.INTRO.back_2_into.sprite.mask=im2;

        var im3 = new PIXI.Graphics();
        im3.clear();
        im3.beginFill(0,1);
        im3.drawRect(0,0,game.INTRO.back_3_into.uni_width,game.INTRO.back_3_into.uni_height);
        im3.endFill();
        game.INTRO.back_3_into.sprite.addChild(im3);
        game.INTRO.back_3_into.sprite.mask=im3;*/

        for (i=1;i<7;i++)
        {
            game.INTRO["s_1_"+i].x=game.INTRO["s_1_"+i].sprite.position.x;
            game.INTRO["s_1_"+i].y=game.INTRO["s_1_"+i].sprite.position.y;
        }

        if (!GLOB_M)
        {
            game.GAME.btn_snd.sprite.visible=false;
            game.GAME.btn_no_snd.sprite.visible=false;
            game.GAME.btn_snd.down_sprite.visible=false;
            game.GAME.btn_no_snd.down_sprite.visible=false;

            game.MAIN.btn_snd.sprite.visible=false;
            game.MAIN.btn_no_snd.sprite.visible=false;
            game.MAIN.btn_snd.down_sprite.visible=false;
            game.MAIN.btn_no_snd.down_sprite.visible=false;
        

            game.CREDITS.btn_snd.sprite.visible=false;
            game.CREDITS.btn_no_snd.sprite.visible=false;
            game.CREDITS.btn_snd.down_sprite.visible=false;
            game.CREDITS.btn_no_snd.down_sprite.visible=false;


            game.LEVELS.btn_snd.sprite.visible=false;
            game.LEVELS.btn_no_snd.sprite.visible=false;
            game.LEVELS.btn_snd.down_sprite.visible=false;
            game.LEVELS.btn_no_snd.down_sprite.visible=false;


            game.INTRO.btn_snd.sprite.visible=false;
            game.INTRO.btn_no_snd.sprite.visible=false;
            game.INTRO.btn_snd.down_sprite.visible=false;
            game.INTRO.btn_no_snd.down_sprite.visible=false;
        }

        function continueGame()
        {
            game.TITLE.sprite.visible = true;
            order.push(game.TITLE);
        }
        if (GLOBAL.API!=0)
        {
            GLOBAL.API.Branding.displaySplashScreen(continueGame);
            var logoData = GLOBAL.API.Branding.getLogo();

            if (logoData.image) 
            {

                var texture = PIXI.Texture.fromImage(logoData.image);
                var btn = new PIXI.Sprite(texture);

                btn.buttonMode = true;
                btn.interactive = true;
                btn.click = btn.tap = function(data)
                {
                    logoData.action();
                    data.originalEvent.stopPropagation();
                    data.originalEvent.preventDefault();
                }

                game.UP_SPR.addChild(btn);
            }

            GLOBAL.LINK = GLOBAL.API.Branding.getLink('more_games');
            var link = document.createElement('a');
    
            // assign the outgoing click     
			/*
            link.href = "javascript:void(0);";
            link.onclick = GLOBAL.LINK.action;
            link.ontouchend = GLOBAL.LINK.action;
            link.setAttribute("id", "spilgames-more-games-btn");
            link.innerHTML = "More Games";
			*/
            
            // Adds the element to the document
            //document.body.appendChild(link);
        }else continueGame();
    }

    function update(tk) 
    {
        if (tk>200)
            tk=200;

        for (i=0;i<GLOBAL.CLOUDS.length;i++)
        {
            GLOBAL.CLOUDS[i].position.x-=GLOBAL.CLOUDS[i].speed*tk/1000;
            if(GLOBAL.CLOUDS[i].position.x<-game.SCREEN_WIDTH)
            {
                GLOBAL.CLOUDS[i].position.x=game.SCREEN_WIDTH;
                GLOBAL.CLOUDS[i].position.y=Math.random()*game.SCREEN_HEIGHT/2-game.SCREEN_HEIGHT*0.2;
                GLOBAL.CLOUDS[i].speed=Math.random()*30+30;
            }
        }

        function get_f(obj)
        {
            if (obj.visible)
            {
                if (obj!=game.GAME.wrays.sprite)
                obj.rotation=0.000000001;

                for (var i=0;i<obj.children.length;i++)
                    get_f(obj.children[i]);
            }
        }

        if (GLOBAL.AFTER_INTRO)
            get_f(game.GUI);

        for (var i=0;i<to_update.length;i++)
        {
            to_update[i](tk);
        }
    }

    function add_to_update(f) 
    {
        to_update.push(f);
    }

    function event(act,wnd,el,id,tag) 
    {
        if (game.GUI_BUSY)
            return;

        if (act=="click")
        {
            switch (wnd)
            {
                case "LEVELS":
                    switch (el)
                    {
                        case "btn_add":
                            GLOBAL.WND_LEVELS.add_level();
                            break;
                    }
                    break;
                case "GAME":
                    switch (el)
                    {
                        case "btn_change":
                            GLOBAL.WND_LEVELS.change();
                            break; 
                        case "btn_edit_erase":
                            GLOBAL.WND_LEVELS.erase();
                            break;
                        case "btn_edit_nail":
                            GLOBAL.WND_LEVELS.nail();
                            break;
                        case "btn_edit_up":
                            GLOBAL.WND_LEVELS.set_path(1);
                            break;
                        case "btn_edit_right":
                            GLOBAL.WND_LEVELS.set_path(2);
                            break;
                        case "btn_edit_down":
                            GLOBAL.WND_LEVELS.set_path(4);
                            break;
                        case "btn_edit_left":
                            GLOBAL.WND_LEVELS.set_path(8);
                            break;
                        case "btn_edit_chain_up":
                            GLOBAL.WND_LEVELS.set_chain(1);
                            break;
                        case "btn_edit_chain_right":
                            GLOBAL.WND_LEVELS.set_chain(2);
                            break;
                        case "btn_edit_chain_down":
                            GLOBAL.WND_LEVELS.set_chain(4);
                            break;
                        case "btn_edit_chain_left":
                            GLOBAL.WND_LEVELS.set_chain(8);
                            break;
                        case "btn_close_win":
                            GLOBAL.WND_GAME.close();
                            break;
                        case "btn_edit":
                            GLOBAL.WND_LEVELS.edit();
                            break;
                        case "btn_edit_save":
                            GLOBAL.WND_LEVELS.save();
                            break;
                    }
                    break;
            }
        }else if (act=="up")
        {    
            switch (wnd)
            {
                case "LEVELS":
                    if (el.substring(0,8)=="btn_lev_")
                    {
                        GLOBAL.CURRENT_LEVEL=el.substring(8,el.length)*1-1;
                        GLOBAL.WND_GAME.show();
                    }
                    switch (el)
                    {
                        case "btn_close":
                            GLOBAL.WND_LEVELS.close();
                            break;
                        case "btn_start":
                            GLOBAL.CURRENT_LEVEL=id;
                            GLOBAL.WND_GAME.show();
                            break;
                        case "btn_no_snd":
                        case "btn_snd":
                            change_snd();
                            break;
                    }
                    break;
                case "INTRO":
                    switch (el)
                    {
                        case "btn_start":
                            GLOBAL.WND_LEVELS.show();
                            break;
                        case "btn_no_snd":
                        case "btn_snd":
                            change_snd();
                            break;
                    }
                    break;  
                case "CREDITS":
                    switch (el)
                    {
                        case "btn_start":
                            GLOBAL.WND_CREDITS.close();
                            break;
                        case "btn_no_snd":
                        case "btn_snd":
                            change_snd();
                            break;
                    }
                    break;  
                case "TITLE":
                    switch (el)
                    {
                        case "btn_start":
                            if (is_snd())
                                GLOB_in_menu.play("none",0,0,-1);
                            game.clear();
                            show_wnd_left(game.MAIN);
                            break;
                    }
                    break; 
                case "MAIN":
                    switch (el)
                    {
                        case "btn_more":
                            if (GLOBAL.LINK!=0)
                            {
                                GLOBAL.LINK.action();
                            }
                            break;
                        case "btn_credits":
                            GLOBAL.WND_CREDITS.show();
                            break;
                        case "btn_start":
                            if (!GLOBAL.INTRO_SHOWED)
                            {
                                show_wnd_left(game.INTRO);
                                GLOBAL.WND_INTRO.start();
                            }else
                            {
                                GLOBAL.WND_LEVELS.show();
                            }
                            break;
                        case "btn_no_snd":
                        case "btn_snd":
                            change_snd();
                            break;
                    }
                    break; 
                case "GAME":
                    switch (el)
                    {
                        case "btn_more":
                            if (GLOBAL.LINK!=0)
                            {
                                GLOBAL.LINK.action();
                            }
                            break;
                        case "btn_restart":
                            GLOBAL.WND_GAME.restart();
                            break;
                        case "btn_next_win":
                            GLOBAL.WND_GAME.next();
                            if (GLOBAL.API!=0)
                                GLOBAL.API.GameBreak.request(pauseGame, resumeGame);
                            break;
                        case "btn_new_close_win":
                        case "btn_close_win":
                        case "btn_close":
                            GLOBAL.WND_GAME.close();
                            break;
                        case "btn_no_snd":
                        case "btn_snd":
                            change_snd();
                            break;
                    }
                    break;
            }
        }else if (act=="start")
        {
            GLOBAL.START_X=id;
            GLOBAL.START_Y=tag;

            var mx=Math.floor((GLOBAL.START_X-GLOBAL.MAP_X)/GLOBAL.EL_WIDTH);
            var my=Math.floor((GLOBAL.START_Y-GLOBAL.MAP_Y)/GLOBAL.EL_HEIGHT);

            if ((mx>=0)&&(mx<GLOBAL.MAP_WIDTH)&&(my>=0)&&(my<GLOBAL.MAP_HEIGHT))
            {
                GLOBAL.WND_GAME.non_pass();
                if (GLOBAL.WND_GAME.on_click(mx,my)>0)
                {
                    GLOBAL.START_X=-1;
                    GLOBAL.WND_GAME.update_map();
                }
            }
        }else if (act=="move")
        {
            if (GLOBAL.START_X<0)
                return;

            if (GLOBAL.ELEMENT!=null)
            {
                GLOBAL.ELEMENT.sprite.position.x=GLOBAL.ELEMENT.old_x*GLOBAL.EL_WIDTH;
                GLOBAL.ELEMENT.sprite.position.y=GLOBAL.ELEMENT.old_y*GLOBAL.EL_HEIGHT;
            }

            var dx=id-GLOBAL.START_X;
            var dy=tag-GLOBAL.START_Y;
            
            if ((dx==0)&&(dy==0))
                return;

            if (Math.abs(dx)>Math.abs(dy))
            {
                if (dx>0)
                    dx=1;
                else dx=-1;
                dy=0;
            }else
            {
                if (dy>0)
                    dy=1;
                else dy=-1;
                dx=0;
            }

            var mx=Math.floor((GLOBAL.START_X-GLOBAL.MAP_X)/GLOBAL.EL_WIDTH);
            var my=Math.floor((GLOBAL.START_Y-GLOBAL.MAP_Y)/GLOBAL.EL_HEIGHT);
            if (dx!=0)
                GLOBAL.PROG=Math.abs(id-GLOBAL.START_X)/GLOBAL.EL_WIDTH;
            else
                GLOBAL.PROG=Math.abs(tag-GLOBAL.START_Y)/GLOBAL.EL_HEIGHT;
            //game.deb(Math.floor(GLOBAL.START_Y)+":"+Math.floor(GLOBAL.MAP_Y)+":"+Math.floor(game.GUI.position.y));

            if ((mx>=0)&&(mx<GLOBAL.MAP_WIDTH)&&(my>=0)&&(my<GLOBAL.MAP_HEIGHT)&&(mx+dx>=0)&&(mx+dx<GLOBAL.MAP_WIDTH)&&(my+dy>=0)&&(my+dy<GLOBAL.MAP_HEIGHT))
            {
                GLOBAL.WND_GAME.non_pass();
                GLOBAL.WND_GAME.on_move(mx,my,dx,dy);

                if (GLOBAL.PROG>=0.5)
                {
                    GLOBAL.PROG=0.5;
                    GLOBAL.WND_GAME.update_map();
                }else 
                    GLOBAL.WND_GAME.on_set_position();

            }
        }else if (act=="finish")
        {
            if (GLOBAL.START_X<0)
                return;
            var dx=id-GLOBAL.START_X;
            var dy=tag-GLOBAL.START_Y;
            
            if ((dx==0)&&(dy==0))
                return;

            if (Math.abs(dx)>Math.abs(dy))
            {
                if (dx>0)
                    dx=1;
                else dx=-1;
                dy=0;
            }else
            {
                if (dy>0)
                    dy=1;
                else dy=-1;
                dx=0;
            }

            var mx=Math.floor((GLOBAL.START_X-GLOBAL.MAP_X)/GLOBAL.EL_WIDTH);
            var my=Math.floor((GLOBAL.START_Y-GLOBAL.MAP_Y)/GLOBAL.EL_HEIGHT);


            if ((mx>=0)&&(mx<GLOBAL.MAP_WIDTH)&&(my>=0)&&(my<GLOBAL.MAP_HEIGHT)&&(mx+dx>=0)&&(mx+dx<GLOBAL.MAP_WIDTH)&&(my+dy>=0)&&(my+dy<GLOBAL.MAP_HEIGHT))
            {
                GLOBAL.WND_GAME.non_pass();
                GLOBAL.WND_GAME.on_move(mx,my,dx,dy);
                GLOBAL.WND_GAME.update_map();
            }
        }
    }

    function AVK_WND_INTRO()
    {
        var here=this;

        this.set_property=function(p)
        {
            var prog=p*p;
            if (p<=2)
                game.INTRO.back_1.sprite.alpha=p/2;

            if ((p>2)&&(p<=3))
            {
                game.INTRO.back_1.sprite.alpha=1;
                prog=p-2;
                prog=Math.sqrt(prog);
                i=1;

                game.INTRO["s_1_"+i].sprite.scale.y=2-prog;
                game.INTRO["s_1_"+i].sprite.scale.x=2-prog;
                game.INTRO["s_1_"+i].sprite.position.x=game.INTRO["s_1_"+i].x-game.INTRO["s_1_"+i].uni_width*(1-prog);
                //game.INTRO["s_1_"+i].sprite.position.y=game.INTRO["s_1_"+i].y+game.INTRO["s_1_"+i].y*3*(1-prog);
                //game.INTRO["s_1_"+i].sprite.visible=(game.INTRO["s_1_"+i].sprite.position.y<game.INTRO.back_1_into.uni_height);
            }
            if ((p>2.5)&&(p<=3.5))
            {
                i=2;
                prog=p-2.5;
                prog=Math.sqrt(prog);

                game.INTRO["s_1_"+i].sprite.scale.y=2-prog;
                game.INTRO["s_1_"+i].sprite.scale.x=2-prog;
                game.INTRO["s_1_"+i].sprite.position.x=game.INTRO["s_1_"+i].x-i*game.INTRO["s_1_"+i].uni_width*(1-prog);
                //game.INTRO["s_1_"+i].sprite.position.y=game.INTRO["s_1_"+i].y+game.INTRO["s_1_"+i].y*3*(1-prog);
                //game.INTRO["s_1_"+i].sprite.visible=(game.INTRO["s_1_"+i].sprite.position.y<game.INTRO.back_1_into.uni_height);
            }

            if ((p>3)&&(p<=4))
            {
                i=3;
                prog=p-3;
                prog=Math.sqrt(prog);
                
                game.INTRO["s_1_"+i].sprite.scale.y=2-prog;
                game.INTRO["s_1_"+i].sprite.scale.x=2-prog;
                game.INTRO["s_1_"+i].sprite.position.x=game.INTRO["s_1_"+i].x-i*game.INTRO["s_1_"+i].uni_width*(1-prog);
                //game.INTRO["s_1_"+i].sprite.position.y=game.INTRO["s_1_"+i].y+game.INTRO["s_1_"+i].y*3*(1-prog);
                //game.INTRO["s_1_"+i].sprite.visible=(game.INTRO["s_1_"+i].sprite.position.y<game.INTRO.back_1_into.uni_height);
            }

            if ((p>3.5)&&(p<=4.5))
            {
                i=4;
                prog=p-3.5;
                prog=Math.sqrt(prog);
                
                game.INTRO["s_1_"+i].sprite.scale.y=2-prog;
                game.INTRO["s_1_"+i].sprite.scale.x=2-prog;
                game.INTRO["s_1_"+i].sprite.position.x=game.INTRO["s_1_"+i].x-i*game.INTRO["s_1_"+i].uni_width*(1-prog);
                //game.INTRO["s_1_"+i].sprite.position.y=game.INTRO["s_1_"+i].y+game.INTRO["s_1_"+i].y*3*(1-prog);
                //game.INTRO["s_1_"+i].sprite.visible=(game.INTRO["s_1_"+i].sprite.position.y<game.INTRO.back_1_into.uni_height);
            }

            if ((p>4.5)&&(p<=6.5))
            {
                game.INTRO.back_2.sprite.alpha=(p-4.5)/2;
                game.INTRO.up_2.sprite.visible=true;
            }

            if ((p>4.5)&&(p<=11))
            {
                i=5;
                prog=p-4.5;
                prog=Math.sin(prog*Math.PI*2.3);
                
                game.INTRO["s_1_"+i].sprite.position.x=game.INTRO["s_1_"+i].x+prog*20;
                game.INTRO["s_1_"+i].sprite.position.y=game.INTRO["s_1_"+i].y+Math.abs(prog)*20;
                game.INTRO["s_1_"+i].sprite.rotation=prog/20;
            }

            if ((p>9)&&(p<=10))
            {
                game.INTRO.back_3.sprite.alpha=(p-9);
                game.INTRO.up_3.sprite.visible=true;
            }

            if ((p>9)&&(p<=11))
            {
                i=6;
                prog=(p-10)/2;
                
                game.INTRO["s_1_"+i].sprite.position.x=game.INTRO["s_1_"+i].x+(1-prog)*30;
                game.INTRO["s_1_"+i].sprite.position.y=game.INTRO["s_1_"+i].y+(1-prog)*15;
                game.INTRO["s_1_"+i].sprite.rotation=prog/20;
            }
        }

        this.finish=function()
        {
            GLOBAL.AFTER_INTRO=true;
        }

        this.start=function()
        {
            for (var i=1;i<5;i++)
            {
                game.INTRO["s_1_"+i].sprite.scale.y=2;
                game.INTRO["s_1_"+i].sprite.scale.x=2;
                game.INTRO["s_1_"+i].sprite.position.x=game.INTRO["s_1_"+i].x-i*game.INTRO["s_1_"+i].uni_width;
                //game.INTRO["s_1_"+i].sprite.position.y=game.INTRO["s_1_"+i].y*4;
                //var t=game.INTRO["s_1_"+i].sprite.position.y;
                //var f=(t<game.INTRO.back_1_into.uni_height);
                //game.INTRO["s_1_"+i].sprite.visible=f;
            }
            game.INTRO.back_1.sprite.visible=true;
            game.INTRO.back_1.sprite.alpha=0;
            game.INTRO.back_2.sprite.alpha=0;
            game.INTRO.back_3.sprite.alpha=0;
            game.INTRO.up_1.sprite.visible=true;
            game.INTRO.up_2.sprite.visible=false;
            game.INTRO.up_3.sprite.visible=false;

            game.ACT.start("show_intro",here,here.finish);
        }
    }

    GLOBAL.WND_INTRO=new AVK_WND_INTRO();

    function AVK_WND_EMITTERS()
    {
        var emitters=[];
        var dym=[];
        var sled=[];

        function AVK_emitter()
        {
            this.x=0;
            this.y=0;
            this.active=false;
            this.time=0;
        }

        function create_sled()
        {
            if (sled.length==0)
            {
                sled.push(game.GAME.sled);
                game.GAME.sled.sprite.visible=true;
                game.GAME.sled.sprite.anchor.x=0.5;
                game.GAME.sled.sprite.anchor.y=0.5;
                return game.GAME.sled;
            }

            var d=game.GAME.sled.make_copy();
            sled.push(d);
            game.GAME.dym_place.add(d);
            d.sprite.anchor.x=0.5;
            d.sprite.anchor.y=0.5;
            d.sprite.visible=true;
            return d;
        }

        function add_sled(x,y)
        {
            var d=null;

            for(var i=0;i<sled.length;i++)
            {
                if(!sled[i].sprite.visible)
                {
                    d=sled[i];
                    d.sprite.visible=true;
                    break;
                }
            }

            if (d==null)
                d=create_sled();

            d.sprite.rotation=Math.random()*Math.PI*2;
            d.sprite.alpha=1;
            d.sprite.scale.x=1;
            d.sprite.scale.y=1;
            d.sprite.position.x=x+Math.random()*RANDOM_EMITTER/5;
            d.sprite.position.y=y+Math.random()*RANDOM_EMITTER/5;
            d.rot=Math.random()*Math.PI*2;
            d.time=(DYM_LIFE+Math.random()*DYM_LIFE/2)/3;
            d.max_time=d.time;
        }

        this.move=function(id,x,y)
        {
            emitters[id].x=x;
            emitters[id].y=y;
        }

        this.start=function(x,y,sled,rate)
        {
            for (var i=0;i<emitters.length;i++)
            {
                if (!emitters[i].active)
                {
                    emitters[i].active=true;
                    emitters[i].time=1;
                    emitters[i].x=x;
                    emitters[i].y=y;
                    emitters[i].sled=sled;
                    emitters[i].rate=rate;
                    return i;
                }
            }
            emitters.push(new AVK_emitter());
            emitters[i].active=true;
            emitters[i].time=1;
            emitters[i].x=x;
            emitters[i].y=y;
            emitters[i].sled=sled;
            emitters[i].rate=rate;
            return i
        }

        this.finish=function(id)
        {
            emitters[id].active=false;
        }

        function move_sled(dym,tk)
        {
            //var x=dym.sprite.position.x+tk*((dym.sprite.position.x-game.SCREEN_WIDTH/2)/300+Math.cos(dym.rot+dym.sprite.alpha/2))/70;
            //var y=dym.sprite.position.y+tk*((dym.sprite.position.y-game.SCREEN_HEIGHT)/300+Math.sin(dym.rot+dym.sprite.alpha/2))/70;
            
            dym.time-=tk;
            if (dym.time<=0)
            {
                dym.sprite.visible=false;
                return;
            }
            dym.sprite.alpha=dym.time/dym.max_time;
            dym.sprite.scale.x=5-4*dym.time/dym.max_time;
            dym.sprite.scale.y=5-4*dym.time/dym.max_time;
            dym.sprite.rotation+=tk*(0.5-Math.random())/600;
            //dym.sprite.position.x=x;
            dym.sprite.position.y-=tk/100;
        }

        add_to_update(update);
        function update(tk)
        {//
            for (var i=0;i<emitters.length;i++)
            {
                if (emitters[i].active)
                {
                    emitters[i].time-=tk;
                    if (emitters[i].time<=0)
                    {
                        if (emitters[i].sled)
                        {
                            emitters[i].time=EMITTER_RATE/10/emitters[i].rate;
                            add_sled(emitters[i].x,emitters[i].y);
                        }
                    }
                }
            }

            for (i=0;i<sled.length;i++)
            {
                if (sled[i].sprite.visible)
                {
                    move_sled(sled[i],tk);
                }
            }
        }
    }
    function AVK_WND_TITLE()
    {//переменные
        var here=this;
        var stop_ui=false;
        this.level_btn=null;

        game.MAIN.btn_start.center();
        game.CREDITS.btn_start.center();
        game.TITLE.btn_start.center();
        game.INTRO.btn_start.center();

        function AVK_BTN_SCALE()
        {
            this.set_property = function (newVal)
            {
                if (stop_ui)
                    return;
                game.MAIN.btn_start.sprite.scale.x=1+0.1*newVal;
                game.MAIN.btn_start.sprite.scale.y=1-0.1*newVal;
                game.CREDITS.btn_start.sprite.scale.x=1+0.1*newVal;
                game.CREDITS.btn_start.sprite.scale.y=1-0.1*newVal; 
                game.TITLE.btn_start.sprite.scale.x=1+0.1*newVal;
                game.TITLE.btn_start.sprite.scale.y=1-0.1*newVal;

                if (GLOBAL.AFTER_INTRO)
                {
                    game.INTRO.btn_start.sprite.scale.x=1+0.1*newVal;
                    game.INTRO.btn_start.sprite.scale.y=1-0.1*newVal;
                }

                if (here.level_btn!=null)
                {
                    here.level_btn.sprite.scale.x=1+0.1*newVal;
                    here.level_btn.sprite.scale.y=1-0.1*newVal;
                }
            }
        }

        var btn_scale=new AVK_BTN_SCALE();

        function finish_scale()
        {
            if (stop_ui)
                return;
            game.ACT.start("ui_scale",btn_scale,finish_scale);
        }

        this.stop_scale_ui = function()
        {
            stop_ui=true;
        }

        finish_scale();
    }

    function AVK_WND_GAME()
    {//переменные
        var here=this;
        var local_map=new Array(GLOBAL.MAX_ARRAY*GLOBAL.MAX_ARRAY);
        this.external_map=local_map;
        var tmp_map=new Array(GLOBAL.MAX_ARRAY*GLOBAL.MAX_ARRAY);
        var path_map=new Array(GLOBAL.MAX_ARRAY*GLOBAL.MAX_ARRAY);
        var n0=false;
        var n1=false;
        var n2=false;
        var n3=false;
        var tick=0;

        function AVK_tutor()
        {
            var here=this;
            var src_x=0;
            var src_y=0;
            var trg_x=0;
            var trg_y=0;
            var f=false;
            var finished=true;

            this.set_property=function(p)
            {
                if (p<1/4)
                {
                    if (game.GAME.back_arrow.sprite.alpha<1)
                        game.GAME.back_arrow.sprite.alpha=p*4;
                    game.GAME.arrow.sprite.alpha=p*4;
                    game.GAME.arrow.sprite.scale.x=2-p*4;
                    game.GAME.arrow.sprite.scale.y=2-p*4;
                }else
                {
                    game.GAME.back_arrow.sprite.alpha=1;
                    game.GAME.arrow.sprite.position.x=(src_x+(trg_x-src_x)*(p-1/4)*4/3) * GLOBAL.EL_WIDTH+GLOBAL.EL_WIDTH/2;
                    game.GAME.arrow.sprite.position.y=(src_y+(trg_y-src_y)*(p-1/4)*4/3) * GLOBAL.EL_HEIGHT+GLOBAL.EL_HEIGHT/2;
                    
                    game.GAME.arrow.sprite.alpha=1-(p-1/4)*4/12;
                    game.GAME.arrow.sprite.scale.x=1+(p-1/4)*4/12;
                    game.GAME.arrow.sprite.scale.y=1+(p-1/4)*4/12;
                }
            }

            this.stop=function()
            {
                f=false;
                game.GAME.help_place.sprite.visible=false;
            }

            this.finish=function()
            {
                game.GAME.help_place.sprite.visible=false;
                finished=true;
                if (f)
                    here.start(src_x,src_y,trg_x,trg_y,1);
            }

            this.start=function(sx,sy,tx,ty,a)
            {
                src_x=sx;
                src_y=sy;
                trg_x=tx;
                trg_y=ty;
                game.GAME.help_place.sprite.visible=true;
                game.GAME.arrow.sprite.position.x=sx * GLOBAL.EL_WIDTH+GLOBAL.EL_WIDTH/2;
                game.GAME.arrow.sprite.position.y=sy * GLOBAL.EL_HEIGHT+GLOBAL.EL_HEIGHT/2;
                game.GAME.arrow.sprite.alpha=0;
                game.GAME.back_arrow.sprite.position.x=sx * GLOBAL.EL_WIDTH+GLOBAL.EL_WIDTH/2;
                game.GAME.back_arrow.sprite.position.y=sy * GLOBAL.EL_HEIGHT+GLOBAL.EL_HEIGHT/2;
                game.GAME.back_arrow.sprite.alpha=a;
                

                f=true;
                if (finished)
                {
                    game.ACT.start("tutor",here,here.finish);
                    finished=false;
                }
            }
        }

        this.tutorial=new AVK_tutor();
        this.tutorial.stop();
            
        function AVK_WND_SCALE()
        {
            var here=this;
            var sc_mul=0.03;
            this.napr=0;
            this.obj=null;
            this.uw=0;
            this.uh=0;
            
            this.set_property=function(p)
            {
                here.obj.sprite.scale.x=1;
                here.obj.sprite.scale.y=1;

                switch(here.napr)
                {
                    case 0:
                        here.obj.sprite.scale.y=1-sc_mul*p;
                        break;
                    case 1:
                        here.obj.sprite.scale.x=1-sc_mul*p;
                        here.obj.sprite.position.x=this.obj.scx+here.uw*sc_mul*p;
                        break;
                    case 2:
                        here.obj.sprite.scale.y=1-sc_mul*p;
                        here.obj.sprite.position.y=this.obj.scy+here.uh*sc_mul*p;
                        break;
                    case 3:
                        here.obj.sprite.scale.x=1-sc_mul*p;
                        break;
                }
            }

            this.start=function()
            {
                for (var i=0;i<local_map.length;i++)
                {
                    var current_block=local_map[i];

                    if ((current_block!=null)&&((current_block.x!=current_block.old_x)||(current_block.y!=current_block.old_y)))
                    {
                        here.obj=current_block;
                        this.obj.scx=this.obj.sprite.position.x;
                        this.obj.scy=this.obj.sprite.position.y;
                        if (current_block.x>current_block.old_x)
                            here.napr=1;
                        else if (current_block.x<current_block.old_x)
                            here.napr=3;
                        else if (current_block.y<current_block.old_y)
                            here.napr=0;
                        else here.napr=2;

                        here.uw=current_block.uni_width;
                        here.uh=current_block.uni_height;
                        if (current_block.cb!=null)
                        {
                            here.uw=current_block.cb.uni_width;
                            here.uh=current_block.cb.uni_height;
                            break;
                        }
                    }
                }
                GLOBAL.STEPS++;
                GLOBAL.WND_GAME.tutorial.stop();
                game.ACT.start("scale",here,final_position);
            }
        }
        var scale_block=new AVK_WND_SCALE();

        function update(tk)
        {
            game.GAME.wrays.sprite.rotation+=tk/1500;

            if (GLOBAL.SKACHKA)
                game.HERO_S.go.update(tk);

            if (!GLOBAL.RUN)
                return;

            game.GAME.txt_level.set_text("Level "+(1+GLOBAL.CURRENT_LEVEL));
            game.GAME.txt_moves.set_text(""+GLOBAL.STEPS);
            game.GAME.txt_step_1.set_text(""+GLOBAL.STEP1);
            game.GAME.txt_step_2.set_text(""+GLOBAL.STEP2);
            game.GAME.txt_step_3.set_text(""+GLOBAL.STEP3);

            if (GLOBAL.PRINCESS_PAUSE>0)
            {
                GLOBAL.PRINCESS_PAUSE-=tk;
                if (GLOBAL.PRINCESS_PAUSE<0)
                    GLOBAL.PRINCESS_PAUSE=0;
            }else
            {
                if (game.PRINCESS.prnc.update(tk))
                {
                    GLOBAL.PRINCESS_PAUSE=2000;
                }
            }

            game.HERO_S.stay.update(tk);

            if (tick>0)
            {
                tick-=tk;
                if (tick<=0)
                    tick=0;
                
                update_position((GLOBAL.MOVE_TICK-tick)/GLOBAL.MOVE_TICK);
                if (tick==0)
                    scale_block.start();
            }
        }
        add_to_update(update);

        function refresh_blocks()
        {
            for (var i=0;i<local_map.length;i++)
            {
                var current_block=local_map[i];
                if (current_block!=null)
                {
                    var place=current_block.sprite.parent;
                    if (place!=null)
                    {
                        place.removeChild(current_block.sprite);
                        place.addChild(current_block.sprite);
                    }
                }
            }
        }

        function get_cool_block(p1,p2,ch,md)
        {
            for (var i=0;i<game.DATA.blocks.length;i++)
            {
                if ((game.DATA.blocks[i].path==p1)&&(game.DATA.blocks[i].path_2==p2)&&(game.DATA.blocks[i].chain==ch))
                {
                    return CONTAINER.get_object("BLOCKS",game.DATA.blocks[i].block+"_"+md,null);
                }
            }
            return null;
        }

        function init_level()
        {
            tick=0;
            GLOBAL.STEPS=0;
            if (game.HERO_S.go.sprite.parent!=null)
                game.HERO_S.go.sprite.parent.removeChild(game.HERO_S.go.sprite);

            game.HERO_S.stay.sprite.visible=true;
            game.GAME.start_3.sprite.visible=false;
            game.GAME.finish_3.sprite.visible=false;

            for (i=0;i<local_map.length;i++)
                if (local_map[i]!=null)
                {
                    if (local_map[i].cb!=null)
                    {
                        CONTAINER.free(local_map[i].cb);
                        local_map[i].cb=null;
                    }
                    CONTAINER.free(local_map[i]);
                    local_map[i]=null;
                }

            var level=GLOBAL.LEVELS[GLOBAL.CURRENT_LEVEL];
            GLOBAL.STEP1=level.steps_1;
            GLOBAL.STEP2=level.steps_2;
            GLOBAL.STEP3=level.steps_3;
            game.GAME.txt_moves.set_text("");
            game.GAME.txt_step_1.set_text("");
            game.GAME.txt_step_2.set_text("");
            game.GAME.txt_step_3.set_text("");
            GLOBAL.SUFIX=level.sufix;
            var map=game.GAME[level.map_name];
            map.add(game.GAME["start"+GLOBAL.SUFIX]);
            map.add(game.GAME["finish"+GLOBAL.SUFIX]);
            GLOBAL.MAP_X=map.sprite.position.x;
            GLOBAL.MAP_Y=map.sprite.position.y; 
            GLOBAL.MAP_WIDTH=level.map_width;
            GLOBAL.MAP_HEIGHT=level.map_height;

            GLOBAL.EL_WIDTH=map.uni_width/level.map_width;
            GLOBAL.EL_HEIGHT=map.uni_height/level.map_height;
            here.tutorial.stop();
            if (GLOBAL.CURRENT_LEVEL==0)
                here.tutorial.start(2,1,2,2,0);

            //game.GAME["start"+GLOBAL.SUFIX].sprite.visible=true;
            game.GAME["start"+GLOBAL.SUFIX].x=level.start_x;
            game.GAME["start"+GLOBAL.SUFIX].y=level.start_y;
            if (game.GAME["start"+GLOBAL.SUFIX].x<0)
            {
                game.GAME["start"+GLOBAL.SUFIX].x++;
                game.GAME["start"+GLOBAL.SUFIX].path="path_3"+GLOBAL.SUFIX;
            }
            if (game.GAME["start"+GLOBAL.SUFIX].x>=GLOBAL.MAP_WIDTH)
            {
                game.GAME["start"+GLOBAL.SUFIX].x--;
                game.GAME["start"+GLOBAL.SUFIX].path="path_1"+GLOBAL.SUFIX;
            }
            if (game.GAME["start"+GLOBAL.SUFIX].y<0)
            {
                game.GAME["start"+GLOBAL.SUFIX].y++;
                 game.GAME["start"+GLOBAL.SUFIX].path="path_0"+GLOBAL.SUFIX;
            }
            if (game.GAME["start"+GLOBAL.SUFIX].y>=GLOBAL.MAP_HEIGHT)
            {
                game.GAME["start"+GLOBAL.SUFIX].y--;
                game.GAME["start"+GLOBAL.SUFIX].path="path_2"+GLOBAL.SUFIX;
            }

            game.GAME["start"+GLOBAL.SUFIX].sprite.position.x=level.start_x*GLOBAL.EL_WIDTH+(GLOBAL.EL_WIDTH-game.GAME["start"+GLOBAL.SUFIX].uni_width)/2;
            game.GAME["start"+GLOBAL.SUFIX].sprite.position.y=level.start_y*GLOBAL.EL_HEIGHT+(GLOBAL.EL_HEIGHT-game.GAME["start"+GLOBAL.SUFIX].uni_height)/2;

            //game.GAME["finish"+GLOBAL.SUFIX].sprite.visible=true;
            game.GAME["finish"+GLOBAL.SUFIX].x=level.finish_x;
            game.GAME["finish"+GLOBAL.SUFIX].y=level.finish_y;
            if (game.GAME["finish"+GLOBAL.SUFIX].x<0)
            {
                game.GAME["finish"+GLOBAL.SUFIX].x++;
                game.GAME["finish"+GLOBAL.SUFIX].path="path_3"+GLOBAL.SUFIX;
            }
            if (game.GAME["finish"+GLOBAL.SUFIX].x>=GLOBAL.MAP_WIDTH)
            {
                game.GAME["finish"+GLOBAL.SUFIX].x--;
                game.GAME["finish"+GLOBAL.SUFIX].path="path_1"+GLOBAL.SUFIX;
            }
            if (game.GAME["finish"+GLOBAL.SUFIX].y<0)
            {
                game.GAME["finish"+GLOBAL.SUFIX].y++;
                game.GAME["finish"+GLOBAL.SUFIX].path="path_0"+GLOBAL.SUFIX;
            }
            if (game.GAME["finish"+GLOBAL.SUFIX].y>=GLOBAL.MAP_HEIGHT)
            {
                game.GAME["finish"+GLOBAL.SUFIX].y--;
                game.GAME["finish"+GLOBAL.SUFIX].path="path_2"+GLOBAL.SUFIX;
            }

            game.GAME["finish"+GLOBAL.SUFIX].sprite.position.x=level.finish_x*GLOBAL.EL_WIDTH+(GLOBAL.EL_WIDTH-game.GAME["finish"+GLOBAL.SUFIX].uni_width)/2;
            game.GAME["finish"+GLOBAL.SUFIX].sprite.position.y=level.finish_y*GLOBAL.EL_HEIGHT+(GLOBAL.EL_HEIGHT-game.GAME["finish"+GLOBAL.SUFIX].uni_height)/2;

            game.GAME.cursor.sprite.position.x=GLOBAL.MAP_X+GLOBAL.EL_WIDTH/2;
            game.GAME.cursor.sprite.position.y=GLOBAL.MAP_Y+GLOBAL.EL_HEIGHT/2;
            GLOBAL.EDIT_X=0;
            GLOBAL.EDIT_Y=0;

            for (var y=0;y<level.map_height;y++)
                for (var x=0;x<level.map_width;x++)
                {
                    var id=level.blocks[x+y*level.map_width].id;
                    if (id>=0)
                    {
                        var current_block=CONTAINER.get_object("GAME","block"+GLOBAL.SUFIX,map);
                        current_block.sprite.position.x=x*GLOBAL.EL_WIDTH;
                        current_block.sprite.position.y=y*GLOBAL.EL_HEIGHT;
                        current_block.cb=null;
                        current_block.x=x;
                        current_block.y=y;
                        current_block.old_x=x;
                        current_block.old_y=y;
                        current_block.md=level.blocks[x+y*level.map_width].md;
                        local_map[x+y*GLOBAL.MAX_ARRAY]=current_block;

                        for (i=0;i<4;i++)
                        {
                            current_block["path_"+i+GLOBAL.SUFIX].sprite.visible=(id-Math.floor(id/2)*2==1);
                            id=Math.floor(id/2);
                        }
                        current_block["nail"+GLOBAL.SUFIX].sprite.visible=!level.blocks[x+y*level.map_width].mv;
                        id=level.blocks[x+y*level.map_width].ch;
                        for (i=0;i<4;i++)
                        {
                            current_block["chain_"+i+GLOBAL.SUFIX].sprite.visible=(id-Math.floor(id/2)*2==1);
                            id=Math.floor(id/2);
                        }

                        var p1=level.blocks[x+y*level.map_width].id;
                        var p2=0;
                        var ch=level.blocks[x+y*level.map_width].ch;
                        if (ch==2)
                        {
                            p2=level.blocks[x+1+y*level.map_width].id;
                        }else if (ch==4)
                        {
                            p2=level.blocks[x+(y+1)*level.map_width].id;
                        }

                        var cb=get_cool_block(p1,p2,ch,level.blocks[x+y*level.map_width].md);

                        if (cb)
                        {
                            current_block["blocks_back"+GLOBAL.SUFIX].sprite.visible=false;
                            current_block.cb=cb;
                            current_block.add(cb);
                        }else 
                        {
                            if (((ch==8)&&(local_map[x-1+y*GLOBAL.MAX_ARRAY].cb!=null))||((ch==1)&&(local_map[x+(y-1)*GLOBAL.MAX_ARRAY].cb!=null)))
                                current_block["blocks_back"+GLOBAL.SUFIX].sprite.visible=false;
                            else
                                current_block["blocks_back"+GLOBAL.SUFIX].sprite.visible=true;
                        }
                    }
                }
        }

        function test_block(x,y,path)
        {
            var bl=local_map[x+y*GLOBAL.MAX_ARRAY];
            if ((bl!=null)&&(bl[path].sprite.visible))
                return true;

            return false;
        }

        function verify_step(x,y,step)
        {
            if (path_map[x+y*GLOBAL.MAX_ARRAY]==1000000)
            {
                path_map[x+y*GLOBAL.MAX_ARRAY]=step;

                return true;//local_map[x+y*GLOBAL.MAX_ARRAY]["path_0"+GLOBAL.SUFIX].sprite.visible;;
            }

            if (path_map[x+y*GLOBAL.MAX_ARRAY]>=0)
                return false;

            path_map[x+y*GLOBAL.MAX_ARRAY]=step;

            if ((x>0)&&(test_block(x,y,"path_3"+GLOBAL.SUFIX))&&(test_block(x-1,y,"path_1"+GLOBAL.SUFIX))&&(verify_step(x-1,y,step+1)))
                return true;

            if ((x<GLOBAL.MAP_WIDTH-1)&&(test_block(x,y,"path_1"+GLOBAL.SUFIX))&&(test_block(x+1,y,"path_3"+GLOBAL.SUFIX))&&(verify_step(x+1,y,step+1)))
                return true;

            if ((y>0)&&(test_block(x,y,"path_0"+GLOBAL.SUFIX))&&(test_block(x,y-1,"path_2"+GLOBAL.SUFIX))&&(verify_step(x,y-1,step+1)))
                return true;

            if ((y<GLOBAL.MAP_HEIGHT-1)&&(test_block(x,y,"path_2"+GLOBAL.SUFIX))&&(test_block(x,y+1,"path_0"+GLOBAL.SUFIX))&&(verify_step(x,y+1,step+1)))
                return true;
        }

        function verify_path()
        {
            if (game.GAME.editor.sprite.visible)
                return false;

            for (var i=0;i<path_map.length;i++)
                path_map[i]=-1;

            path_map[game.GAME["finish"+GLOBAL.SUFIX].x+game.GAME["finish"+GLOBAL.SUFIX].y*GLOBAL.MAX_ARRAY]=1000000;
            var bl=local_map[game.GAME["finish"+GLOBAL.SUFIX].x+game.GAME["finish"+GLOBAL.SUFIX].y*GLOBAL.MAX_ARRAY];
            var fin=local_map[game.GAME["start"+GLOBAL.SUFIX].x+game.GAME["start"+GLOBAL.SUFIX].y*GLOBAL.MAX_ARRAY];
            var f=false;
            if ((bl!=null)&&(bl[game.GAME["finish"+GLOBAL.SUFIX].path].sprite.visible)&&(fin!=null)&&(fin[game.GAME["start"+GLOBAL.SUFIX].path].sprite.visible))
                f=verify_step(game.GAME["start"+GLOBAL.SUFIX].x,game.GAME["start"+GLOBAL.SUFIX].y,0);

            if (f)
            {
                for (i=0;i<GLOBAL.PATH.length;i++)
                    GLOBAL.PATH[i]=-1;

                i=0;

                function fill_steps(x,y,step)
                {
                    if ((x<0)||(y<0)||(x>GLOBAL.MAP_WIDTH-1)||(y>GLOBAL.MAP_HEIGHT-1)||(step<0))
                        return;

                    if (path_map[x+y*GLOBAL.MAX_ARRAY]==step)
                    {
                        GLOBAL.PATH[i]=x;
                        GLOBAL.PATH[i+1]=y;
                        i+=2;
                        fill_steps(x-1,y,step-1);
                        fill_steps(x+1,y,step-1);
                        fill_steps(x,y-1,step-1);
                        fill_steps(x,y+1,step-1);
                    }
                }
                fill_steps(game.GAME["finish"+GLOBAL.SUFIX].x,game.GAME["finish"+GLOBAL.SUFIX].y,path_map[game.GAME["finish"+GLOBAL.SUFIX].x+game.GAME["finish"+GLOBAL.SUFIX].y*GLOBAL.MAX_ARRAY]);
            }
            return f;
        }

        function move_block(current_block,dx,dy)
        {
            if (current_block.pass)
                return;
            current_block.x=current_block.old_x;
            current_block.y=current_block.old_y;
            current_block.pass=true;

            for (var i=0;i<4;i++)
                if (current_block["chain_"+i+GLOBAL.SUFIX].sprite.visible)
                {//мы не будем проверять корректность соединения,т.е. выходы за границы поля
                    switch(i)
                    {
                        case 0:
                            move_block(local_map[current_block.x+(current_block.y-1)*GLOBAL.MAX_ARRAY],dx,dy);
                            break;
                        case 1:
                            move_block(local_map[current_block.x+1+current_block.y*GLOBAL.MAX_ARRAY],dx,dy);
                            break;
                        case 2:
                            move_block(local_map[current_block.x+(current_block.y+1)*GLOBAL.MAX_ARRAY],dx,dy);
                            break;
                        case 3:
                            move_block(local_map[current_block.x-1+current_block.y*GLOBAL.MAX_ARRAY],dx,dy);
                            break;
                    }
                }

            current_block.x+=dx;
            current_block.y+=dy;

            current_block.sprite.position.x=(current_block.old_x+(current_block.x-current_block.old_x)*GLOBAL.PROG)*GLOBAL.EL_WIDTH;
            current_block.sprite.position.y=(current_block.old_y+(current_block.y-current_block.old_y)*GLOBAL.PROG)*GLOBAL.EL_HEIGHT;

        }

        function update_position(pr)
        {
            for (var i=0;i<local_map.length;i++)
            {
                var current_block=local_map[i];
                if ((current_block!=null)&&((current_block.x!=current_block.old_x)||(current_block.y!=current_block.old_y)))
                {
                    current_block.sprite.position.x=(current_block.old_x+(current_block.x-current_block.old_x)*pr)*GLOBAL.EL_WIDTH;
                    current_block.sprite.position.y=(current_block.old_y+(current_block.y-current_block.old_y)*pr)*GLOBAL.EL_HEIGHT;
                }
            }
        }
                    
        function final_position()
        {
            for (var i=0;i<local_map.length;i++)
            {
                if (local_map[i]!=null)
                {
                    tmp_map[local_map[i].x+local_map[i].y*GLOBAL.MAX_ARRAY]=local_map[i];
                }
            }

            for (i=0;i<local_map.length;i++)
            {
                var bl=tmp_map[i];
                local_map[i]=bl;
                if (bl!=null)
                {
                    bl.old_x=bl.x;
                    bl.old_y=bl.y;
                }
            }

            game.GUI_BUSY=false;
            refresh_blocks();

            if (verify_path())
                here.show_win();
        }


        this.update_map=function()
        {
            GLOBAL.START_X=-1;
            for (var i=0;i<local_map.length;i++)
                if ((local_map[i]!=null)&&(i!=local_map[i].x+local_map[i].y*GLOBAL.MAX_ARRAY))
                {
                    tick=GLOBAL.MOVE_TICK-GLOBAL.MOVE_TICK*GLOBAL.PROG;
                    if (tick==0)
                        tick++;
                    game.GUI_BUSY=true;
                }
        }

        function update_napr(bl)
        {
            if (bl.pass)
                return;

            bl.pass=true;
            if (bl["nail"+GLOBAL.SUFIX].sprite.visible)
            {
                n0=false;
                n1=false;
                n2=false;
                n3=false;
                return;
            }

            var up=null;
            var dn=null;
            var lt=null;
            var rt=null;
            var mx=bl.x;
            var my=bl.y;

            if (mx>0)
            {
                lt=local_map[mx-1+my*GLOBAL.MAX_ARRAY];
                if (lt!=null)
                {
                    if(bl["chain_3"+GLOBAL.SUFIX].sprite.visible)
                        update_napr(lt);
                    else
                        n3=false;
                }
            }else n3=false;

            if (mx<GLOBAL.MAP_WIDTH-1)
            {
                rt=local_map[mx+1+my*GLOBAL.MAX_ARRAY];
                if (rt!=null)
                {
                    if(bl["chain_1"+GLOBAL.SUFIX].sprite.visible)
                        update_napr(rt);
                    else
                        n1=false;
                }
            }else n1=false;

            if (my>0)
            {
                up=local_map[mx+(my-1)*GLOBAL.MAX_ARRAY];
                if (up!=null)
                {
                    if(bl["chain_0"+GLOBAL.SUFIX].sprite.visible)
                        update_napr(up);
                    else
                        n0=false;
                }
            }else n0=false;
            
            if (my<GLOBAL.MAP_HEIGHT-1)
            {
                dn=local_map[mx+(my+1)*GLOBAL.MAX_ARRAY];
                if (dn!=null)
                {
                    if (bl["chain_2"+GLOBAL.SUFIX].sprite.visible)
                        update_napr(dn);
                    else
                        n2=false;
                }
            }else n2=false;
        }

        this.on_click=function(mx,my)
        {
            if (game.GAME.editor.sprite.visible)
            {
                if ((GLOBAL.EDIT_X!=mx)||(GLOBAL.EDIT_Y!=my))
                {
                    GLOBAL.EDIT_X=mx;
                    GLOBAL.EDIT_Y=my;
                    game.GAME.cursor.sprite.position.x=GLOBAL.MAP_X+GLOBAL.EL_WIDTH*(mx+0.5);
                    game.GAME.cursor.sprite.position.y=GLOBAL.MAP_Y+GLOBAL.EL_HEIGHT*(my+0.5);
                    return 1;
                }
            }

            var bl=local_map[mx+my*GLOBAL.MAX_ARRAY];
            GLOBAL.ELEMENT=bl;
            return -1;

            if (bl!=null)
            {
                if (bl["nail"+GLOBAL.SUFIX].sprite.visible)
                    return -1;

                n0=true;
                n1=true;
                n2=true;
                n3=true;

                update_napr(bl);

                var dx=0;
                var dy=0;
                var cnt=0;

                if (n0)
                {
                    dy=-1;
                    cnt++;
                }
                if (n1)
                {
                    dx=1;
                    cnt++;
                }
                if (n2)
                {
                    dy=1;
                    cnt++;
                }
                if (n3)
                {
                    dx=-1;
                    cnt++;
                }

                if (cnt!=1)
                    return -1;
                
                var move_id=(dx+1)+10*(dy+1);
                here.non_pass();
                move_block(bl,dx,dy);
                return move_id;
            }
        }

        this.non_pass=function()
        {
            for (var i=0;i<local_map.length;i++)
            {
                if (local_map[i]!=null)
                    local_map[i].pass=false;
                tmp_map[i]=null;
            }
        }

        this.on_set_position=function()
        {
            for (var i=0;i<local_map.length;i++)
            {
                var current_block=local_map[i];
                if (current_block!=null)
                {
                    current_block.sprite.position.x=(current_block.old_x+(current_block.x-current_block.old_x)*GLOBAL.PROG)*GLOBAL.EL_WIDTH;
                    current_block.sprite.position.y=(current_block.old_y+(current_block.y-current_block.old_y)*GLOBAL.PROG)*GLOBAL.EL_HEIGHT;

                    current_block.x=current_block.old_x;
                    current_block.y=current_block.old_y;
                }
            }
        }

        this.on_move=function(mx,my,dx,dy)
        {
            if (game.GAME.editor.sprite.visible)
            {
                if ((GLOBAL.EDIT_X!=mx)||(GLOBAL.EDIT_Y!=my))
                {
                    GLOBAL.EDIT_X=mx;
                    GLOBAL.EDIT_Y=my;
                    game.GAME.cursor.sprite.position.x=GLOBAL.MAP_X+GLOBAL.EL_WIDTH*(mx+0.5);
                    game.GAME.cursor.sprite.position.y=GLOBAL.MAP_Y+GLOBAL.EL_HEIGHT*(my+0.5);
                    return;
                }
            }

            var bl=local_map[mx+my*GLOBAL.MAX_ARRAY];
            GLOBAL.ELEMENT=bl;
            if (bl==null)
                return;
            n0=true;
            n1=true;
            n2=true;
            n3=true;

            update_napr(bl);

            if (dx==0)
            {
                if ((dy==-1)&&(!n0))
                    return;
                else if ((dy==1)&&(!n2))
                    return;
            }else
            {
                if ((dx==-1)&&(!n3))
                    return;
                else if ((dx==1)&&(!n1))
                    return;
            }
            
            here.non_pass();
            move_block(bl,dx,dy);
        }

        function on_show()
        {
            GLOBAL.RUN=true; 
        }

        function pre_show()
        {
            game.GAME.win.sprite.visible=false;
            game.GAME.shad.sprite.scale.x=10.2;
            game.GAME.shad.sprite.scale.y=10.2;
            game.GAME.shad.sprite.position.x=-10;
            game.GAME.shad.sprite.position.y=-10;
            game.GAME.shad.sprite.visible=false;
            game.GAME.wrays.sprite.visible=false;
            game.GAME.txt_level.set_text("");
            init_level();
        }

        this.restart=function()
        {
            pre_show();
            on_show();
        }

        this.next=function()
        {
            if (GLOBAL.CURRENT_LEVEL<GLOBAL.LEVELS.length-1)
            {
                GLOBAL.CURRENT_LEVEL++;
                pre_show();
                on_show();
            }else here.close();
        }

        this.refresh=function()
        {
            GLOBAL.START_X=-1;
            GLOBAL.ELEMENT=null;
            pre_show();
            on_show();
        }

        this.show=function()
        {
            pre_show();
            GLOBAL.SHOW_HANDLER=on_show;
            show_wnd_left(game.GAME);
            game.GAME.txt_level.set_text("Level "+(1+GLOBAL.CURRENT_LEVEL));
        }

        this.close=function()
        {
            GLOBAL.RUN=false;
            hide_wnd_left();
        }

        function AVK_show_wnd()
        {
            var here=this;
            this.wnd=null;

            this.set_property=function (val)
            {
                here.wnd.sprite.position.y=-this.wnd.uni_height+(this.wnd.y+this.wnd.uni_height)*val;
                game.GAME.shad.sprite.alpha=val;
            }

            this.finish=function ()
            {
                var w=game.GAME.wrays;
                w.sprite.visible=true;
                w.sprite.alpha=0;
                game.ACT.start("alpha",w);
                game.GUI_BUSY=false;
            }
        }
        this.wnd=new AVK_show_wnd();

        function AVK_show_finish()
        {
            var here=this;
            var cnt;
            var i=0;
            var on_finish;
            var x=2;
            var y=2.7;
            var next_x;
            var next_y;
            var delta_x;
            var delta_y;
            var emit=0;

            this.set_property=function (val)
            {
                if (next_x>x)
                    game.HERO_S.go.sprite.scale.x=-1;
                else if (next_x<x)
                    game.HERO_S.go.sprite.scale.x=1;
                game.HERO_S.go.sprite.position.x=(x+(next_x-x)*val) *GLOBAL.EL_WIDTH+delta_x;
                game.HERO_S.go.sprite.position.y=(y+(next_y-y)*val)*GLOBAL.EL_HEIGHT+delta_y;

                GLOBAL.WND_EMITTERS.move(emit,game.HERO_S.go.sprite.position.x+game.HERO_S.go.uni_width/2+game.GAME.go_place.sprite.position.x,game.HERO_S.go.sprite.position.y+game.HERO_S.go.uni_height+game.GAME.go_place.sprite.position.y);

                if (game.HERO_S.go.sprite.scale.x==-1)
                    game.HERO_S.go.sprite.position.x+=game.HERO_S.go.uni_width;
            }

            this.finish=function ()
            {
                cnt--;
                if (cnt<=0)
                {
                    on_finish();
                    GLOBAL.WND_EMITTERS.finish(emit);
                }else
                {
                    i+=2;
                    x=next_x;
                    y=next_y;
                    next_x=GLOBAL.PATH[i];
                    next_y=GLOBAL.PATH[i+1];
                    if (next_x<0)
                    {
                        next_x=1;
                        next_y=-0.7;
                    }
                    game.ACT.start("finish_way",here,here.finish);
                }
            }

            this.start=function (c,f)
            {
                x=2;
                y=2.7;
                next_x=GLOBAL.PATH[0];
                next_y=GLOBAL.PATH[1];

                game.GUI_BUSY=true;
                cnt=c;
                on_finish=f;
                game.GAME.go_place.add(game.HERO_S.go);
                if (next_x>x)
                    game.HERO_S.go.sprite.scale.x=-1;
                else
                    game.HERO_S.go.sprite.scale.x=1;

                delta_x=game.GAME.hero_place.sprite.position.x-x*GLOBAL.EL_WIDTH-game.GAME.go_place.sprite.position.x;
                delta_y=game.GAME.hero_place.sprite.position.y-y*GLOBAL.EL_HEIGHT-game.GAME.go_place.sprite.position.y;
                game.HERO_S.go.sprite.visible=true;
                game.HERO_S.stay.sprite.visible=false;
                game.HERO_S.go.sprite.position.x=x*GLOBAL.EL_WIDTH+delta_x;
                game.HERO_S.go.sprite.position.y=y*GLOBAL.EL_HEIGHT+delta_y;
                if (game.HERO_S.go.sprite.scale.x==-1)
                    game.HERO_S.go.sprite.position.x+=game.HERO_S.go.uni_width;

                i=0;
                game.ACT.start("finish_way",here,here.finish);
                GLOBAL.SKACHKA=true;
                emit=GLOBAL.WND_EMITTERS.start(game.HERO_S.go.sprite.position.x+game.HERO_S.go.uni_width/2+game.GAME.go_place.sprite.position.x,game.HERO_S.go.sprite.position.y+game.HERO_S.go.uni_height+game.GAME.go_place.sprite.position.y,true,1);
            }
        }
        this.finish=new AVK_show_finish();

        function AVK_zvezda()
        {
            var here=this;
            var prog;
            var f=0;

            this.set_property=function(p)
            {
                if (p<1/4)
                {
                    if (!game.GAME.zv1.sprite.visible)
                        game.GUI_BUSY=false;
                    
                    game.GAME.zv1.sprite.alpha=p*4;
                    game.GAME.zv1.sprite.scale.x=3-8*p;
                    game.GAME.zv1.sprite.scale.y=3-8*p;
                    game.GAME.zv1.sprite.position.y=game.GAME.zv1.y*p*4;
                }else if (p<2/4)
                {
                    if (!game.GAME.zv1.sprite.visible)
                        game.GUI_BUSY=false;
                    else if (f==0)
                    {
                        f++;
                        /*if (is_snd())
                            GLOB_match.play();*/
                    }

                    if (game.GAME.zv1.sprite.visible)
                    {
                        game.GAME.win.sprite.scale.x=1-0.050*Math.sin(Math.PI*(p-1/4)*4) ;
                        game.GAME.win.sprite.scale.y=game.GAME.win.sprite.scale.x;
                        game.GAME.win.sprite.position.x=game.GAME.win.x+game.SCREEN_WIDTH*(1-game.GAME.win.sprite.scale.x)/2;
                        game.GAME.win.sprite.position.y=game.GAME.win.y+game.SCREEN_HEIGHT*(1-game.GAME.win.sprite.scale.y)/2;

                        if (prog==0)
                        {
                            game.GAME.zv1.sprite.alpha=1;
                            game.GAME.zv1.sprite.scale.x=1;
                            game.GAME.zv1.sprite.scale.y=1;
                            game.GAME.zv1.sprite.position.y=game.GAME.zv1.y;
                            //born_stars(game.GAME.zv1.sprite.position.x+game.GAME.zv1.uni_width/2,game.GAME.zv1.sprite.position.y+game.GAME.zv1.uni_height/2);
                            prog++;
                        }
                    }else
                    {
                        game.GAME.win.sprite.scale.x=1;
                        game.GAME.win.sprite.scale.y=1;
                        game.GAME.win.sprite.position.x=game.GAME.win.x;
                        game.GAME.win.sprite.position.y=game.GAME.win.y;
                    }
                    game.GAME.zv1.sprite.alpha=1;
                    game.GAME.zv2.sprite.alpha=(p-1/4)*4;
                    game.GAME.zv2.sprite.scale.x=3-8*(p-1/4);
                    game.GAME.zv2.sprite.scale.y=3-8*(p-1/4);
                    game.GAME.zv2.sprite.position.y=game.GAME.zv2.y*(p-1/4)*4;
                }else if (p<3/4)
                {
                    if (!game.GAME.zv2.sprite.visible)
                        game.GUI_BUSY=false;
                    else if (f==1)
                    {
                        f++;
                       /*if (is_snd())
                            GLOB_match.play();*/
                    }

                    if (game.GAME.zv2.sprite.visible)
                    {
                        game.GAME.win.sprite.scale.x=1-0.1*Math.sin(Math.PI*(p-2/4)*4) ;
                        game.GAME.win.sprite.scale.y=game.GAME.win.sprite.scale.x;
                        game.GAME.win.sprite.position.x=game.GAME.win.x+game.SCREEN_WIDTH*(1-game.GAME.win.sprite.scale.x)/2;
                        game.GAME.win.sprite.position.y=game.GAME.win.y+game.SCREEN_HEIGHT*(1-game.GAME.win.sprite.scale.y)/2;
                        if (prog==1)
                        {
                            game.GAME.zv2.sprite.position.y=game.GAME.zv2.y;
                            game.GAME.zv2.sprite.alpha=1;
                            game.GAME.zv2.sprite.scale.x=1;
                            game.GAME.zv2.sprite.scale.y=1;
                        
                            //born_stars(game.GAME.zv2.sprite.position.x+game.GAME.zv2.uni_width/2,game.GAME.zv2.sprite.position.y+game.GAME.zv2.uni_height/2);
                            prog++;
                        }
                    }else
                    {
                        game.GAME.win.sprite.scale.x=1;
                        game.GAME.win.sprite.scale.y=1;
                        game.GAME.win.sprite.position.x=game.GAME.win.x;
                        game.GAME.win.sprite.position.y=game.GAME.win.y;
                    }
                    
                    game.GAME.zv3.sprite.alpha=(p-2/4)*4;
                    game.GAME.zv3.sprite.scale.x=3-8*(p-2/4);
                    game.GAME.zv3.sprite.scale.y=3-8*(p-2/4);
                    game.GAME.zv3.sprite.position.y=game.GAME.zv3.y*(p-2/4)*4;
                }else
                {
                    if (game.GAME.zv3.sprite.visible)
                    {
                        game.GAME.win.sprite.scale.x=1-0.15*Math.sin(Math.PI*(p-3/4)*4) ;
                        game.GAME.win.sprite.scale.y=game.GAME.win.sprite.scale.x;
                        game.GAME.win.sprite.position.x=game.GAME.win.x+game.SCREEN_WIDTH*(1-game.GAME.win.sprite.scale.x)/2;
                        game.GAME.win.sprite.position.y=game.GAME.win.y+game.SCREEN_HEIGHT*(1-game.GAME.win.sprite.scale.y)/2;
                        if (prog==2)
                        {
                            game.GAME.zv3.sprite.position.y=game.GAME.zv3.y;
                            game.GAME.zv3.sprite.alpha=1;
                            game.GAME.zv3.sprite.scale.x=1;
                            game.GAME.zv3.sprite.scale.y=1;
                            //born_stars(game.GAME.zv3.sprite.position.x+game.GAME.zv3.uni_width/2,game.GAME.zv3.sprite.position.y+game.GAME.zv3.uni_height/2);
                           /*if (is_snd())
                                    GLOB_match.play();*/
                            prog++;
                        }
                    }else
                    {
                        game.GAME.win.sprite.scale.x=1;
                        game.GAME.win.sprite.scale.y=1;
                        game.GAME.win.sprite.position.x=game.GAME.win.x;
                        game.GAME.win.sprite.position.y=game.GAME.win.y;
                    }
                }
            }

            this.finish=function()
            {
                game.GAME.win.sprite.scale.x=1;
                game.GAME.win.sprite.scale.y=1;
                game.GAME.win.sprite.position.x=game.GAME.win.x;
                game.GAME.win.sprite.position.y=game.GAME.win.y;
                game.GUI_BUSY=false;
            }

            this.start=function()
            {
                game.GUI_BUSY=true;
                prog=0;
                f=0;
                game.GAME.zv1.sprite.visible=GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL]>0;
                game.GAME.zv2.sprite.visible=GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL]>1;
                game.GAME.zv3.sprite.visible=GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL]>2;
                game.GAME.zv1.sprite.alpha=0;
                game.GAME.zv2.sprite.alpha=0;
                game.GAME.zv3.sprite.alpha=0;
                game.GAME.zv1.sprite.position.y=0;
                game.GAME.zv2.sprite.position.y=0;
                game.GAME.zv3.sprite.position.y=0;
                game.ACT.start("show_zw",here,here.finish);
            }
        }

        var zvezda=new AVK_zvezda();

        this.show_win=function()
        {
            if (GLOBAL.STEPS<=GLOBAL.STEP1)
            {
                if (GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL]<3)
                    GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL]=3;
            }else if (GLOBAL.STEPS<=GLOBAL.STEP2)
            {
                if (GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL]<2)
                    GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL]=2;
            }else if (GLOBAL.STEPS<=GLOBAL.STEP3)
            {
                if (GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL]<1)
                    GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL]=1;
            }else 
            {
                if (GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL]<0)
                    GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL]=0;
            }

            if (GLOBAL.CURRENT_LEVEL+1<GLOBAL.PROGRESS.length)
            {
                if (GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL+1]<-1)
                    GLOBAL.PROGRESS[GLOBAL.CURRENT_LEVEL+1]=-1;
            }
            save();

            var cnt=1;
            for (i=0;i<GLOBAL.PATH.length;i+=2)
                if (GLOBAL.PATH[i]>=0)
                    cnt++;

            function finish_wnd()
            {
                if (GLOBAL.CURRENT_LEVEL<19)
                {
                    game.GAME.compl.sprite.visible=false;
                    game.GAME.prog.sprite.visible=true;
                }else
                {
                    game.GAME.compl.sprite.visible=true;
                    game.GAME.prog.sprite.visible=false;
                }

                GLOBAL.SKACHKA=false;
                GLOBAL.RUN=false;
                game.GUI_BUSY=true;
                game.GAME.win.sprite.visible=true;
                game.GAME.win.sprite.position.y=-game.GAME.win.uni_height;
                here.wnd.wnd=game.GAME.win;
                game.ACT.start("pr",here.wnd,here.wnd.finish);
                zvezda.start();
            }
            
            GLOBAL.WND_GAME.finish.start(cnt,finish_wnd);
        }

        this.show_loose=function()
        {
            GLOBAL.RUN=false;
        }
    }

    function AVK_WND_CREDITS()
    {
        var here=this;

        function on_show()
        {
        }

        this.show=function()
        {
            show_wnd_right(game.CREDITS);
        }

        this.close=function()
        {
            hide_wnd_right();
        }
    }

    function AVK_WND_LEVELS()
    {//переменные
        var here=this;
        var x=0;
        var y=0;
        var i=0;

        game.GAME.btn_edit_2.sprite.visible=false;
        game.GAME.btn_edit_3.sprite.visible=false;
        game.GAME.btn_edit_4.sprite.visible=false;
        
        game.LEVELS.btn.sprite.visible=false;

        game.GAME.cursor.sprite.anchor.x=0.5;
        game.GAME.cursor.sprite.anchor.y=0.5;

        function on_show()
        {
            if (!GLOBAL.INTRO_SHOWED)
            {
                order[order.length-2]=order[order.length-1];
                order.pop();
                GLOBAL.INTRO_SHOWED=true;
            }
        }

        function reset_level()
        {
            var level=GLOBAL.LEVELS[GLOBAL.CURRENT_LEVEL];

            for (var i=0;i<level.blocks.length;i++)
            {
                level.blocks[i].mx=true;
                level.blocks[i].ch=0;
                level.blocks[i].id=-1;
            }

            for (var x=0;x<level.map_width;x++)
                for (var y=0;y<level.map_height;y++)
                {
                    var current_block=GLOBAL.WND_GAME.external_map[x+y*GLOBAL.MAX_ARRAY];

                    if (current_block!=null)
                    {
                        var tmp=0;
                        for (i=3;i>=0;i--)
                        {
                            tmp*=2;
                            if (current_block["path_"+i+GLOBAL.SUFIX].sprite.visible)
                                tmp++;
                        }
                        level.blocks[x+y*level.map_width].id=tmp;
                        level.blocks[x+y*level.map_width].mv=!current_block["nail"+GLOBAL.SUFIX].sprite.visible;

                        tmp=0;                        
                        for (i=3;i>=0;i--)
                        {
                            tmp*=2;
                            if (current_block["chain_"+i+GLOBAL.SUFIX].sprite.visible)
                                tmp++;
                        }
                        level.blocks[x+y*level.map_width].ch=tmp;
                    }
                }

            
        }

        this.erase=function()
        {
            reset_level();
            var level=GLOBAL.LEVELS[GLOBAL.CURRENT_LEVEL];
            if (level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].id>=0)
            {
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].id=-1;
                if((level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch&1)>0)
                    level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y-1)*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y-1)*level.map_width].ch^4;
                if((level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch&2)>0)
                    level.blocks[GLOBAL.EDIT_X+1+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+1+GLOBAL.EDIT_Y*level.map_width].ch^8;
                if((level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch&4)>0)
                    level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y+1)*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y+1)*level.map_width].ch^1;
                if((level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch&8)>0)
                    level.blocks[GLOBAL.EDIT_X-1+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X-1+GLOBAL.EDIT_Y*level.map_width].ch^2;

                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=0;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].mv=true;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].md=0;
            }else
            {
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].id=0;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=0;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].mv=true;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].md=0;
            }

            var mx=GLOBAL.EDIT_X;
            var my=GLOBAL.EDIT_Y;
            GLOBAL.WND_GAME.refresh();
            GLOBAL.EDIT_X=mx;
            GLOBAL.EDIT_Y=my;
            game.GAME.cursor.sprite.position.x=GLOBAL.MAP_X+GLOBAL.EL_WIDTH*(mx+0.5);
            game.GAME.cursor.sprite.position.y=GLOBAL.MAP_Y+GLOBAL.EL_HEIGHT*(my+0.5);
        }

        this.nail=function()
        {
            reset_level();
            var level=GLOBAL.LEVELS[GLOBAL.CURRENT_LEVEL];
            if (level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].id>=0)
            {
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].mv=!level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].mv;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].md=0;
            }else
            {
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].id=0;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=0;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].mv=false;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].md=0;
            }

            var mx=GLOBAL.EDIT_X;
            var my=GLOBAL.EDIT_Y;
            GLOBAL.WND_GAME.refresh();
            GLOBAL.EDIT_X=mx;
            GLOBAL.EDIT_Y=my;
            game.GAME.cursor.sprite.position.x=GLOBAL.MAP_X+GLOBAL.EL_WIDTH*(mx+0.5);
            game.GAME.cursor.sprite.position.y=GLOBAL.MAP_Y+GLOBAL.EL_HEIGHT*(my+0.5);
        }

        this.set_chain=function(id)
        {
            reset_level();

            var level=GLOBAL.LEVELS[GLOBAL.CURRENT_LEVEL];
            if (level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].id>=0)
            {
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].md=0;
                switch (id)
                {
                    case 1:
                        if ((GLOBAL.EDIT_Y>0)&&(level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y-1)*level.map_width].id>=0))
                        {
                            level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch^id;
                            level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y-1)*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y-1)*level.map_width].ch^4;
                        }
                        break;
                    case 2:
                        if ((GLOBAL.EDIT_X<GLOBAL.MAP_WIDTH-1)&&(level.blocks[GLOBAL.EDIT_X+1+GLOBAL.EDIT_Y*level.map_width].id>=0))
                        {
                            level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch^id;
                            level.blocks[GLOBAL.EDIT_X+1+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+1+GLOBAL.EDIT_Y*level.map_width].ch^8;
                        }
                        break;
                    case 4:
                        if ((GLOBAL.EDIT_Y<GLOBAL.MAP_HEIGHT-1)&&(level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y+1)*level.map_width].id>=0))
                        {
                            level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch^id;
                            level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y+1)*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y+1)*level.map_width].ch^1;
                        }
                        break;
                    case 8:
                        if ((GLOBAL.EDIT_X>0)&&(level.blocks[GLOBAL.EDIT_X-1+GLOBAL.EDIT_Y*level.map_width].id>=0))
                        {
                            level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch^id;
                            level.blocks[GLOBAL.EDIT_X-1+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X-1+GLOBAL.EDIT_Y*level.map_width].ch^2;
                        }
                        break;
                }
            }else
            {
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].id=0;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=0;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].md=0;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].mv=true;
                switch (id)
                {
                    case 1:
                        if ((GLOBAL.EDIT_Y>0)&&(level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y-1)*level.map_width].id>=0))
                        {
                            level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch^id;
                            level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y-1)*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y-1)*level.map_width].ch^4;
                        }
                        break;
                    case 2:
                        if ((GLOBAL.EDIT_X<GLOBAL.MAP_WIDTH-1)&&(level.blocks[GLOBAL.EDIT_X+1+GLOBAL.EDIT_Y*level.map_width].id>=0))
                        {
                            level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch^id;
                            level.blocks[GLOBAL.EDIT_X+1+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+1+GLOBAL.EDIT_Y*level.map_width].ch^8;
                        }
                        break;
                    case 4:
                        if ((GLOBAL.EDIT_Y<GLOBAL.MAP_HEIGHT-1)&&(level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y+1)*level.map_width].id>=0))
                        {
                            level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch^id;
                            level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y+1)*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+(GLOBAL.EDIT_Y+1)*level.map_width].ch^1;
                        }
                        break;
                    case 8:
                        if ((GLOBAL.EDIT_X>0)&&(level.blocks[GLOBAL.EDIT_X-1+GLOBAL.EDIT_Y*level.map_width].id>=0))
                        {
                            level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch^id;
                            level.blocks[GLOBAL.EDIT_X-1+GLOBAL.EDIT_Y*level.map_width].ch=level.blocks[GLOBAL.EDIT_X-1+GLOBAL.EDIT_Y*level.map_width].ch^2;
                        }
                        break;
                }
            }
            var mx=GLOBAL.EDIT_X;
            var my=GLOBAL.EDIT_Y;
            GLOBAL.WND_GAME.refresh();
            GLOBAL.EDIT_X=mx;
            GLOBAL.EDIT_Y=my;
            game.GAME.cursor.sprite.position.x=GLOBAL.MAP_X+GLOBAL.EL_WIDTH*(mx+0.5);
            game.GAME.cursor.sprite.position.y=GLOBAL.MAP_Y+GLOBAL.EL_HEIGHT*(my+0.5);

        }

        this.change=function()
        {
            reset_level();

            var level=GLOBAL.LEVELS[GLOBAL.CURRENT_LEVEL];
            var current_block=level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width];

            var p1=current_block.id;
            var p2=0;
            var ch=current_block.ch;
            var max_var=0;
            if (ch==2)
            {
                p2=current_block.id;
            }else if (ch==4)
            {
                p2=current_block.id;
            }

            for (var i=0;i<game.DATA.blocks.length;i++)
            {
                if ((game.DATA.blocks[i].path==p1)&&(game.DATA.blocks[i].path_2==p2)&&(game.DATA.blocks[i].chain==ch))
                {
                    max_var=game.DATA.blocks[i].var;
                }
            }

            current_block.md++;
            if (current_block.md>=max_var)
                current_block.md=0;

            var mx=GLOBAL.EDIT_X;
            var my=GLOBAL.EDIT_Y;
            GLOBAL.WND_GAME.refresh();
            GLOBAL.EDIT_X=mx;
            GLOBAL.EDIT_Y=my;
            game.GAME.cursor.sprite.position.x=GLOBAL.MAP_X+GLOBAL.EL_WIDTH*(mx+0.5);
            game.GAME.cursor.sprite.position.y=GLOBAL.MAP_Y+GLOBAL.EL_HEIGHT*(my+0.5);
        }

        this.set_path=function(id)
        {
            reset_level();

            var level=GLOBAL.LEVELS[GLOBAL.CURRENT_LEVEL];
            if (level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].id>=0)
            {
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].id=level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].id^id;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].md=0;
            }else
            {
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].id=id;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].ch=0;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].mv=true;
                level.blocks[GLOBAL.EDIT_X+GLOBAL.EDIT_Y*level.map_width].md=0;
            }

            var mx=GLOBAL.EDIT_X;
            var my=GLOBAL.EDIT_Y;
            GLOBAL.WND_GAME.refresh();
            GLOBAL.EDIT_X=mx;
            GLOBAL.EDIT_Y=my;
            game.GAME.cursor.sprite.position.x=GLOBAL.MAP_X+GLOBAL.EL_WIDTH*(mx+0.5);
            game.GAME.cursor.sprite.position.y=GLOBAL.MAP_Y+GLOBAL.EL_HEIGHT*(my+0.5);
        }

        this.add_level=function()
        {
            GLOBAL.LEVELS.push({sufix:"_3",map_name:"game_place",map_width:3,map_height:3,start_x:1,start_y:-1,finish_x:2,finish_y:3,blocks:[{mv:true,ch:0,id:-1},{mv:true,ch:0,id:-1},{mv:true,ch:0,id:-1}, {mv:true,ch:0,id:-1},{mv:true,ch:0,id:-1},{mv:true,ch:0,id:-1}, {mv:true,ch:0,id:-1},{mv:true,ch:0,id:-1},{mv:true,ch:0,id:-1}]});
            var btn=CONTAINER.get_object("LEVELS","btn",game.LEVELS.place);
            btn.sprite.position.x=x;
            btn.sprite.position.y=y;
            btn.txt_num.set_style(1,"AVK_FNT_main","center");
            btn.txt_num.set_text(i+1+"");

            x+=btn.uni_width*1.5;
            if (x+btn.uni_width>game.LEVELS.place.uni_width)
            {
                x=0;
                y+=btn.uni_height*1.5;
            }
            i++;
            here.save();
        }

        function load()
        {
            GLOBAL.LEVELS=AVK_MAP;
            /*
            $$a({   type:'get',//тип запроса: get,post либо head
                    url:'save_map.php',//url адрес файла обработчика
                    data:{"data" : 1},//параметры запроса
                    response:'text',//тип возвращаемого ответа text либо xml
                    
                    success:function (data) {
                        if (data=="Can't find file.")
                            alert(data);
                        else
                        { 
                            GLOBAL.LEVELS=JSON.parse(data);
                            //alert('Loaded!');
                        }
                    }
                });*/
        }

        load();

        this.save=function()
        {
            reset_level();
            var map = JSON.stringify(GLOBAL.LEVELS);
            $$a({   type:'post',//тип запроса: get,post либо head
                    url:'save_map.php',//url адрес файла обработчика
                    data:{"data" : map},//параметры запроса
                    response:'text',//тип возвращаемого ответа text либо xml
                    success:function (data) {
                        alert(data);
                    }
                });
        }

        this.edit=function()
        {
            reset_level();
            game.GAME.editor.sprite.visible=!game.GAME.editor.sprite.visible;
        }

        this.show=function()
        {
            game.GAME.editor.sprite.visible=false;
            game.LEVELS.btn.sprite.visible=false;
            GLOBAL.SHOW_HANDLER=on_show;
            show_wnd_left(game.LEVELS);

            if (game.LEVELS.place.sprite.children.length==0)
            {
                

                /*for (i=0;i<GLOBAL.LEVELS.length;i++)
                {
                    var btn=CONTAINER.get_object("LEVELS","btn",game.LEVELS.place);
                    btn.sprite.position.x=x;
                    btn.sprite.position.y=y;
                    btn.txt_num.set_style(1,"AVK_FNT_main","center");
                    btn.txt_num.set_text(i+1+"");

                    x+=btn.uni_width*1.5;
                    if (x+btn.uni_width>game.LEVELS.place.uni_width)
                    {
                        x=0;
                        y+=btn.uni_height*1.5;
                    }
                }*/
            }
        }

        this.close=function()
        {
            hide_wnd_left();
        }
    }
}