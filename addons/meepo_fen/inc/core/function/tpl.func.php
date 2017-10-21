<?php
load()->func('tpl');
function tpl_nav($data = array()){
	if(empty($data)){
		return '';
	}
		
	$html = '<ul class="nav nav-tabs">';
		
	foreach ($data as $da){
		if($da['active']){
			$html .= '
			
				<li class="active">
					<a href="'.$da['href'].'" >'.$da['title'].'</a>	
				</li>
			
				';
		}else{
			$html .= '
		
				<li>
					<a href="'.$da['href'].'" >'.$da['title'].'</a>
				</li>
		
				';
		}
			
	}
	$html .= '</ul>';
	return $html;
}

function tpl_edit($data = array()){
	if(empty($data)){
		return '';
	}
	$html = '';
	if(!isset($data['label'])){
		foreach ($data as $da){
			$html .= tpl_textarea($da);
		}
	
	}else{
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			$html .= '
			<div class="form-group" style="display:'.($data['display']?'block':'none').'">
				<label class="col-sm-2 control-label">'.$data['label'].'</label>
				<div class="col-sm-8">';
			$html .= tpl_ueditor($data['name'],$data['value']);
			$html .= '<p class="help-block">'.$data['help'].'</p>';
			$html .='</div></div>';
			
		}
	
	}
	return $html;
}

function tpl_textarea($data = array()){
	if(empty($data)){
		return '';
	}
	$html = '';
	if(!isset($data['label'])){
		foreach ($data as $da){
			$html .= tpl_textarea($da);
		}

	}else{
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			$html .= '
			<div class="form-group" style="display:'.($data['display']?'block':'none').'">
				<label class="col-sm-2 control-label">'.$data['label'].'</label>
				<div class="col-sm-8">';
			$html .= '<textarea name="'.$data['name'].'" class="form-control" >'.$data['value'].'</textarea>';
			$html .= '<p class="help-block">'.$data['help'].'</p>';
			$html .='</div></div>';
				
		}

	}
	return $html;
}
function tpl_text($data = array()){
		
	if(empty($data)){
		return '';
	}
	$html = '';
	if(!isset($data['label'])){
		foreach ($data as $da){
			$html .= tpl_text($da);
		}
		
	}else{
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			$html .= '
			<div class="form-group" style="display:'.($data['display']?'block':'none').'">
				<label class="col-sm-2 control-label">'.$data['label'].'</label>
				<div class="col-sm-5">
					<input type="text" name = "'.$data['name'].'" class="form-control" value="'.$data['value'].'">';
			$html .= '<p class="help-block">'.$data['help'].'</p>';
			$html .='</div></div>';
		}
		
	}
	return $html;
}

function tpl_a($data = array()){

	if(empty($data)){
		return '';
	}
	$html = '';
	if(!isset($data['label'])){
		foreach ($data as $da){
			$html .= tpl_a($da);
		}

	}else{
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			$html .= '
			<div class="form-group" style="display:'.($data['display']?'block':'none').'">
				<label class="col-sm-2 control-label">'.$data['label'].'</label>
				<div class="col-sm-5">
					<a href="'.$data['value']['href'].'" class="btn btn-default">'.$data['value']['title'].'</a>';
			$html .= '<p class="help-block">'.$data['help'].'</p>';
			$html .='</div></div>';
		}

	}
	return $html;
}

function tpl_link($data = array()){

	if(empty($data)){
		return '';
	}
	$html = '';
	if(!isset($data['label'])){
		foreach ($data as $da){
			$html .= tpl_text($da);
		}

	}else{
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			$html .= '
			<div class="form-group" style="display:'.($data['display']?'block':'none').'">
				<label class="col-sm-2 control-label">'.$data['label'].'</label>
				<div class="col-sm-5">';
			$html .= tpl_form_field_link($data['name'],$data['value']);
			$html .= '<p class="help-block">'.$data['help'].'</p>';
			$html .='</div></div>';
		}

	}
	return $html;
}

function tpl_radio($data = array()){

	if(empty($data)){
		return '';
	}
	$html = '';
	if(!isset($data['label'])){
		foreach ($data as $da){
			$html .= tpl_radio($da);
		}
	}else{
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			$html .= '
				<div class="form-group" style="display:'.($data['display']?'block':'none').'">
				<label class="col-sm-2 control-label">'.$data['label'].'</label>
		<div class="btn-group" data-toggle="buttons">';
			foreach ($data['value'] as $se){
				if($se['active']){
					$html .= '<label class="btn btn-default active">
					<input type="radio" name="'.$data['name'].'" value="'.$se['value'].'"  checked>'.$se['title'].'
				</label>';
				}else{
					$html .= '<label class="btn btn-default">
					<input type="radio" name="'.$data['name'].'" value="'.$se['value'].'" >'.$se['title'].'
					</label>';
				}
			}
			$html .= '<p class="help-block">'.$data['help'].'</p>';
			$html .='</div></div>';
		}
		
	}
	return $html;
}

function tpl_checkbox($data = array()){

	if(empty($data)){
		return '';
	}
	$html = '';
	if(!isset($data['label'])){
		foreach ($data as $da){
			$html .= tpl_checkbox($da);
		}
	}else{
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			$html .= '
				<div class="form-group" style="display:'.($data['display']?'block':'none').'">
				<label class="col-sm-2 control-label">'.$data['label'].'</label>
		<div class="btn-group" data-toggle="buttons">';
			foreach ($data['value'] as $se){
				if($se['active']){
					$html .= '<label class="btn btn-default active">
					<input type="checkbox" name="'.$data['name'].'" value="'.$se['value'].'"  checked>'.$se['title'].'
				</label>';
				}else{
					$html .= '<label class="btn btn-default">
					<input type="checkbox" name="'.$data['name'].'" value="'.$se['value'].'" >'.$se['title'].'
					</label>';
				}
			}
			$html .= '<p class="help-block">'.$data['help'].'</p>';
			$html .='</div></div>';
		}

	}
	return $html;
}
	
function tpl_img($data = array()){
	load()->func('tpl');
		
	if(empty($data)){
		return '';
	}
		
	$html = '';
	if(!isset($data['label'])){
		foreach ($data as $da){
			$html .= tpl_img($da);
		}
		
	}else{
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			$html .= '
			<div class="form-group" style="display:'.($data['display']?'block':'none').'">
				<label class="col-sm-2 control-label">'.$data['label'].'</label>
				<div class="col-sm-5">
					'.tpl_form_field_image($data['name'],$data['value']);
			$html .= '<p class="help-block">'.$data['help'].'</p>';
			$html .='</div></div>';
		}
		
	}
		
	return $html;
}


function tpl_icon($data = array()){
	load()->func('tpl');

	if(empty($data)){
		return '';
	}

	$html = '';
	if(isset($data['label'])){
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			$html .= '
			<div class="form-group" style="display:'.($data['display']?'block':'none').'">
				<label class="col-sm-2 control-label">'.$data['label'].'</label>
				<div class="col-sm-5">
					'.tpl_form_field_icon($data['name'],$data['value']);
			$html .= '<p class="help-block">'.$data['help'].'</p>';
			$html.='</div></div>';
		}
	}else{
		foreach ($data as $da){
			$html .= tpl_icon($da);
		}
	}
	return $html;
}

function tpl_color($data = array()){
	load()->func('tpl');

	if(empty($data)){
		return '';
	}

	$html = '';
	if(isset($data['label'])){
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			$html .= '
			<div class="form-group" style="display:'.($data['display']?'block':'none').'">
				<label class="col-sm-2 control-label">'.$data['label'].'</label>
				<div class="col-sm-8">
					'.tpl_form_field_color($data['name'],$data['value']);
			$html .= '<p class="help-block">'.$data['help'].'</p>';
					$html .= '
				</div>
			</div>
			';
		}
		
	}else{
		foreach ($data as $da){
			$html .= tpl_color($da);
		}
	}

	return $html;
}


function tpl_daterange($data = array()){
	load()->func('tpl');

	if(empty($data)){
		return '';
	}

	$html = '';
	if(isset($data['label'])){
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			$html .= '
			<div class="form-group" style="display:'.($data['display']?'block':'none').'">
				<label class="col-sm-2 control-label">'.$data['label'].'</label>
				<div class="col-sm-5">
					'.tpl_form_field_daterange($data['name'],$data['value']);
			$html .= '<p class="help-block">'.$data['help'].'</p>';
			$html.='</div></div>';
		}
		
	}else{
		foreach ($data as $da){
			$html .= tpl_daterange($da);
		}
	}

	return $html;
}

function tpl_calendar($data = array()){
	load()->func('tpl');

	if(empty($data)){
		return '';
	}

	$html = '';
	if(isset($data['label'])){
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			$html .= '
			<div class="form-group" style="display:'.($data['display']?'block':'none').'">
				<label class="col-sm-2 control-label">'.$data['label'].'</label>
				<div class="col-sm-5">
					'.tpl_form_field_calendar($data['name'],$data['value']);
			$html .= '<p class="help-block">'.$data['help'].'</p>';
					$html .='
				</div>
			</div>
			';
		}
		
	}else{
		foreach ($data as $da){
			$html .= tpl_calendar($da);
		}
	}

	return $html;
}

function tpl_district($data = array()){
	load()->func('tpl');

	if(empty($data)){
		return '';
	}

	$html = '';
	if(isset($data['label'])){
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			if(!empty($data['type'])){
				unset($data['type']);
				$html .= $data['type']($data);
			}else{
				$html .= '
				<div class="form-group" style="display:'.($data['display']?'block':'none').'">
					<label class="col-sm-2 control-label">'.$data['label'].'</label>
					<div class="col-sm-5">
						'.tpl_form_field_district($data['name'],$data['value']);
				$html .= '<p class="help-block">'.$data['help'].'</p>';
						$html .= '
					</div>
				</div>
				';
			}
		}
		
	}else{
		foreach ($data as $da){
			$html .= tpl_district($da);
		}
	}

	return $html;
}

function tpl_coordinate($data = array()){
	load()->func('tpl');

	if(empty($data)){
		return '';
	}

	$html = '';
	if(isset($data['label'])){
		if(!empty($data['type'])){
			$func = $data['type'];
			unset($data['type']);
			$html .= $func($data);
		}else{
			if(!empty($data['type'])){
				unset($data['type']);
				$html .= $data['type']($data);
			}else{
				$html .= '
				<div class="form-group" style="display:'.($data['display']?'block':'none').'">
					<label class="col-sm-2 control-label">'.$data['label'].'</label>
					<div class="col-sm-5">
						'.tpl_form_field_coordinate($data['name'],$data['value']);
				$html .= '<p class="help-block">'.$data['help'].'</p>';
						$html .='
					</div>
				</div>
				';
			}
		}
		
	}else{
		foreach ($data as $da){
			$html .= tpl_coordinate($da);
		}
	}

	return $html;
}

function tpl_search($data){
	if(empty($data)){
		return '';
	}
	$html = '';
	if(!empty($data['type'])){
		$func = $data['type'];
		unset($data['type']);
		$html .= $func($data);
	}else{
		if(!empty($data['type'])){
			unset($data['type']);
			$html .= $data['type']($data);
		}else{
			$html .= '<div class="panel panel-info">
	<div class="panel-heading">筛选</div>
	<div class="panel-body">
		<form action="'.$data['action'].'" method="get" class="form-horizontal" role="form" id="form1">';
			if(!empty($data['items'])){
				foreach ($data['items'] as $item){
					$html .= $item['type']($item);
				}
				
			}
			$html .='<div class="form-group">
				<label class="col-xs-12 col-sm-2 col-md-2 col-lg-1 control-label"></label>
				<div class="col-sm-8 col-lg-9 col-xs-12">
					<input class="btn btn-primary" id="" type="submit" value="搜索">
					<input type="hidden" name="token" value="'.$_W['token'].'">
				</div>
			</div> 
		</form>';
			
		}
	}
}


function tpl_table($data){
	if(empty($data)){
		return '';
	}
	$html = '<table class="table">';
	if(!empty($data['type'])){
		$func = $data['type'];
		unset($data['type']);
		$html .= $func($data);
	}else{
		if(!empty($data['type'])){
			unset($data['type']);
			$html .= $data['type']($data);
		}else{
			if($data['th']){
				$html .='
					<thead>
				';
				
				foreach ($data['th'] as $th){
					$html .='
						<th>'.$th['title'].'</th>
					';
				}
				
				$html .='
					</thead>
				';
			}
			
			if($data['tbody']){
				$html .='
					<tbody>
				';
				
				foreach ($data['td'] as $td){
					$html .='<tr>';
					foreach ($td as $t){
						$html .= '<td>'.$t['value'].'</td>';
					}
					$html .='</tr>';
				}
			
				$html .='
					</tbody>
				';
			}
			
		}
	}
}
function tpl_all($data = array()){
	if(empty($data)){
		return '';
	}
	$html = '';
	foreach ($data as &$da){
		if(!isset($da['type'])){
			$html .= tpl_all($da);
		}else{
			$func = $da['type'];
			unset($da['thype']);
			$html .= $func($da);
		}
	}
	return $html;
}
