function selectFileImage(fileObj,data) { 
    var file = fileObj.files['0'];  
    var Orientation = null;  
	data.width = data.width==null?0:data.width;
	data.height = data.height==null?0:data.height;
    if (file) {  
        var rFilter = /^(image\/jpeg|image\/png)$/i; // 检查图片格式  
        if (!rFilter.test(file.type)) {  
			if(data.error)
				data.error('请选择jpeg、png格式的图片');  
            return;  
        }  

        EXIF.getData(file, function() {  
            EXIF.getAllTags(this);   
            Orientation = EXIF.getTag(this, 'Orientation'); 
        }); 
          
        var oReader = new FileReader();  
        oReader.onload = function(e) {  
            var image = new Image();  
			image.src = e.target.result;
            image.onload = function() {  
                var expectWidth = this.naturalWidth;  
                var expectHeight = this.naturalHeight; 
				if(expectWidth<data.width||expectHeight<data.height){
					if(data.error)
						data.error('请上传宽度大于'+data.width+'px，高度大于'+data.width+'px 的图片'); 	
					return;
				}
                if (this.naturalWidth > this.naturalHeight && this.naturalWidth > 800) {  
                    expectWidth = 800;  
                    expectHeight = expectWidth * this.naturalHeight / this.naturalWidth;  
                } else if (this.naturalHeight > this.naturalWidth && this.naturalHeight > 1200) {  
                    expectHeight = 1200;  
                    expectWidth = expectHeight * this.naturalWidth / this.naturalHeight;  
                }  
                var canvas = document.createElement("canvas");  
                var ctx = canvas.getContext("2d");  
                canvas.width = expectWidth;  
                canvas.height = expectHeight;  
                ctx.drawImage(this, 0, 0, expectWidth, expectHeight);  
                var base64 = null;  
				var rotate = 0;
				if(Orientation != "" && Orientation != 1){   
					switch(Orientation){  
						case 6://需要顺时针（向左）90度旋转  
							rotate = 90;
							break;  
						case 8://需要逆时针（向右）90度旋转  
							rotate = 270; 
							break;  
						case 3://需要180度旋转  
							rotate = 180; 
							break;  
					}         
				}  
				base64 = canvas.toDataURL("image/jpeg", 0.8); 
				if(data.callback)
					data.callback(base64,rotate);
            };  
        };  
        oReader.readAsDataURL(file);  
    }  
}  

function selectPhoteImage(src,data) { 
	if(!src){
		if(data.error)
			data.error('找不到对应图片');
		return;  		
	}
	data.width = data.width==null?0:data.width;
	data.height = data.height==null?0:data.height;	
    var Orientation = null; 
	var image = new Image();  
	image.onload = function() {
        EXIF.getData(this, function() {  
            EXIF.getAllTags(this);   
            Orientation = EXIF.getTag(this, 'Orientation'); 
        });	
		var expectWidth = this.naturalWidth;  
		var expectHeight = this.naturalHeight;  
		if(expectWidth<data.width||expectHeight<data.height){
			if(data.error)
				data.error('请上传宽度大于'+data.width+'px，高度大于'+data.width+'px 的图片'); 	
			return;
		}
		if (this.naturalWidth > this.naturalHeight && this.naturalWidth > 800) {  
			expectWidth = 800;  
			expectHeight = expectWidth * this.naturalHeight / this.naturalWidth;  
		} else if (this.naturalHeight > this.naturalWidth && this.naturalHeight > 1200) {  
			expectHeight = 1200;  
			expectWidth = expectHeight * this.naturalWidth / this.naturalHeight;  
		}  
		var canvas = document.createElement("canvas");  
		var ctx = canvas.getContext("2d");  
		canvas.width = expectWidth;  
		canvas.height = expectHeight; 
		//alert(expectWidth+':'+expectHeight); 
		ctx.drawImage(this, 0, 0, expectWidth, expectHeight);  
		var base64 = null;  
		var rotate = 0;	
		if (navigator.userAgent.match(/iphone/i)) { 
			if(Orientation != "" && Orientation != 1){   
				switch(Orientation){  
					case 6://需要顺时针（向左）90度旋转  
						rotate = 90;
						break;  
					case 8://需要逆时针（向右）90度旋转  
						rotate = 270; 
						break;  
					case 3://需要180度旋转  
						rotate = 180; 
						break;  
				}         
			}  
			base64 = canvas.toDataURL("image/jpeg", 0.8); 
		}else if (navigator.userAgent.match(/Android/i)) {// 修复android  
			base64 = canvas.toDataURL("image/jpeg", 0.8);
		}else{  
			if(Orientation != "" && Orientation != 1){  
				switch(Orientation){  
					case 6://需要顺时针（向左）90度旋转  
						rotate = 90;
						break;  
					case 8://需要逆时针（向右）90度旋转  
						rotate = 270; 
						break;  
					case 3://需要180度旋转  
						rotate = 180; 
						break;  
				}          
			}  
			base64 = canvas.toDataURL("image/jpeg", 0.8); 
		}  
		if(data.callback)
			data.callback(base64,rotate);
	} 
	image.src = src;  
}  

