/**
* put the jsonObject`s element to the form
*/
function jsonObjectToForm(form, jsonObject){

	for(i = 0, max = form.elements.length; i < max; i++) {
	  try{//解决 xhtml 标准解析 对象不正确问题
		var e = form.elements[i];
		var eName = e.name;
		if(eName.indexOf('.') > 0){
			dotIndex = eName.indexOf('.');
			parentName = eName.substring(0, dotIndex);
			childName = eName.substring(dotIndex+1);
			//handle the eName repeatly, pack it into the jsonObject
			eValue = iterValueFromJsonObject(jsonObject, parentName, childName);
		}else{
			eValue = jsonObject[eName];
		}
		if((typeof(eValue) == 'boolean') || (eValue && eValue != "undefined" && eValue != "null")){
			switch(e.type){
				case 'checkbox': 
				case 'radio': 
					if(e.value == eValue){
						e.checked = true;
					}
					break;
				case 'hidden': 
				case 'password': 
				case 'textarea':
				case 'text': 
					e.value = epsSetValuesForInput(e,eValue);
					break;
				case 'select-one':
				case 'select-multiple':
					for(j = 0; j < e.options.length; j++){
						op = e.options[j];
						//alert("eName : " + eName + "; op value : " + op.value + "; eValue : " + eValue);
						if(op.value == String(eValue)){
							op.selected = true;
						}
					}
					break;
				case 'button': 
				case 'file': 
				case 'image': 
				case 'reset': 
				case 'submit': 
				default:  
			}
		}
	  }catch(e){}
	}
}
function json2Object(objectId,json){
	$("#"+objectId).find("div span[id]").each(function(i,n){
		try{
			epsSetValues(n,eval('(json.' + n.id + ')'))
			//$(n).html(eval('(json.' + n.id + ')').replace(/\n/g,"<br>").replace(/\s/g,"&nbsp;&nbsp;"))
		}catch(e){
			$(n).html("")
		}
	})
}
function epsSetValues(o,v){
	switch(String($(o).attr("tabType")).toLocaleLowerCase()){
	case "input":
	    $(o).find("input").val(v)
	    break;
	case "double":
	    $(o).html(new Number(v).toFixed(2));
	    break;
	case "datesimple":
	    $(o).html(v.substring(0,10));
	    break;
	case "textarea":
	    $(o).find("textarea").val(v)
	    break;
	default:  
		$(o).html(v.toString().replace(/\n/g,"<br>").replace(/\s/g,"&nbsp;&nbsp;"))
}
}
function epsSetValuesForInput(o,v){
	switch(String($(o).attr("tabType")).toLocaleLowerCase()){
	case "double":
	    return new Number(v).toFixed(2);
	    break;
	case "datesimple":
	    return v.substring(0,10);
	    break;
	default:  
		return v;
	}
}
/**
* invoke json data :
* 1: a.bs[0].id
* 2: a["bs"][0]["id"]
* translate form into json
*/
function formToJsonObject(form){
	var jsonObject = {};
	for(i = 0, max = form.elements.length; i < max; i++) {
		var e = form.elements[i];
		var em = new Array();
		if(e.type == 'select-multiple'){
			for(j = 0; j < e.options.length; j++){
				op = e.options[j];
				if(op.selected){
					em[em.length] = op.value;
				}
			}
		}
		switch(e.type){
			case 'checkbox': 
			case 'radio': 
				if (!e.checked) { break; } 
			case 'hidden': 
			case 'password': 
			case 'select-one':
			case 'select-multiple':
			case 'textarea':
			case 'text': 
				eName = e.name;
				if(eName==""){
					break;
				}
				if(e.type == 'select-multiple'){
					eValue = em;
				}else{
					eValue = encodeURI($.trim(e.value))//.replace(new RegExp('(["\\])', 'g'), '\\$1');
				}
				//if eName has attributes
				if(eName.indexOf('.') > 0){
					dotIndex = eName.indexOf('.');
					parentName = eName.substring(0, dotIndex);
					childName = eName.substring(dotIndex+1);
					//handle eName`s attributes, pack them into jsonObject
					jsonObject[eName] = eValue;
					iterJsonObject(jsonObject, parentName, childName, eValue);
				}else{
					jsonObject[eName] = eValue;
				}
				break; 
			case 'button': 
			case 'file': 
			case 'image': 
			case 'reset': 
			case 'submit': 
			default:  
		}
	}
	return jsonObject;
}

/**
* translate form element to json data repeatly
*/
function iterJsonObject(jsonObject, parentName, childName, eValue){
	//pArrayIndex : is Array
	pArrayIndex = parentName.indexOf('[');
	//is Collection, else just attribute
	if(pArrayIndex < 0){
		var child = jsonObject[parentName];
		if(!child){
			jsonObject[parentName] = {};
		}
		dotIndex = childName.indexOf('.');
		if(dotIndex > 0){
			iterJsonObject(jsonObject[parentName], childName.substring(0, dotIndex), childName.substring(dotIndex+1), eValue);
		}else{
			jsonObject[parentName][childName] = eValue;
		}
	}else{
		pArray = jsonObject[parentName.substring(0, pArrayIndex)];
		//if it isn`t exist a js Array, then init a Array
		if(!pArray){
			jsonObject[parentName.substring(0, pArrayIndex)] = new Array();
		}
		//get the Array index, and judget whether js object exist
		arrayIndex = parentName.substring(pArrayIndex+1, parentName.length-1);
		var c = jsonObject[parentName.substring(0, pArrayIndex)][arrayIndex];
		if(!c){
			jsonObject[parentName.substring(0, pArrayIndex)][arrayIndex] = {};
		}
		dotIndex = childName.indexOf('.');
		if(dotIndex > 0){
			iterJsonObject(jsonObject[parentName.substring(0, pArrayIndex)][arrayIndex], childName.substring(0, dotIndex), childName.substring(dotIndex+1), eValue);
		}else{
			jsonObject[parentName.substring(0, pArrayIndex)][arrayIndex][childName] = eValue;
		}
	}
}

/**
* settle json data to the form repeatly
*/
function iterValueFromJsonObject(jsonObject, parentName, childName){
	//pArrayIndex : is Array
	pArrayIndex = parentName.indexOf('[');
	//is Array, else is attribute
	try{
		if(pArrayIndex < 0){
			dotIndex = childName.indexOf('.');
			if(dotIndex > 0){
				return iterValueFromJsonObject(jsonObject[parentName], childName.substring(0, dotIndex), childName.substring(dotIndex+1));
			}else{
				
					return jsonObject[parentName][childName]
				
			}
		}else{
			pArray = jsonObject[parentName.substring(0, pArrayIndex)];
			//get the Array index, and judget whether js object exist
			arrayIndex = parentName.substring(pArrayIndex+1, parentName.length-1);
			var c = jsonObject[parentName.substring(0, pArrayIndex)][arrayIndex];
			dotIndex = childName.indexOf('.');
			if(dotIndex > 0){
				return iterValueFromJsonObject(jsonObject[parentName.substring(0, pArrayIndex)][arrayIndex], childName.substring(0, dotIndex), childName.substring(dotIndex+1));
			}else{
				return jsonObject[parentName.substring(0, pArrayIndex)][arrayIndex][childName]
			}
		}
	}catch(e){
		return "";
	}
}

/**
 *  ??form ??name??"_Arr"??????????????????????????????JSON??????
 * Param??JSON??????FORM ????
 * Author??ZhangHy
 * Since: 2008-12-13
 **/
 function ArrToJsonObject(jsonObject,form){
	var max = form.elements.length;
	var nameArr = new Array();
	var index = 0;
	var flag = true;
	for(var i=0;i<max ;i++){
		e = form.elements[i];
		var name = e.name;
		var subname = name.substr(name.length-4);
		for(var k=0 ;k<nameArr.length; k++){
			if(name == nameArr[k]){
				flag = false;
			}
		}
		if(subname == "_Arr" && flag == true){
			var Arr = document.getElementsByName(name);
			var jsonArr = [];
			for(var j=0;j<Arr.length;j++){
				jsonArr[j]=Arr[j].value;
			}
			jsonObject[name] = jsonArr;
			nameArr[index] = name;
			index+=1;
		}
	}
}

//reconstruct the json util javascript

if (!this.JsonUtils) {
    JsonUtils = {};
}

(function (){

	/**
	* put the jsonObject`s element to the form
	*/
	JsonUtils.jsonObjectToForm = function(form, jsonObject){
		for(i = 0, max = form.elements.length; i < max; i++) {
			e = form.elements[i];
			eName = e.name;
			if(eName.indexOf('.') > 0){
				dotIndex = eName.indexOf('.');
				parentName = eName.substring(0, dotIndex);
				childName = eName.substring(dotIndex+1);
				//handle the eName repeatly, pack it into the jsonObject
				eValue = iterValueFromJsonObject(jsonObject, parentName, childName);
			}else{
				eValue = jsonObject[eName];
			}
			if((typeof(eValue) == 'boolean') || (eValue && eValue != "undefined" && eValue != "null")){
				switch(e.type){
					case 'checkbox': 
					case 'radio': 
						if(e.value == eValue){
							e.checked = true;
						}
						break;
					case 'hidden': 
					case 'password': 
					case 'textarea':
					case 'text': 
						e.value = eValue;
						break;
					case 'select-one':
					case 'select-multiple':
						for(j = 0; j < e.options.length; j++){
							op = e.options[j];
							//alert("eName : " + eName + "; op value : " + op.value + "; eValue : " + eValue);
							if(op.value == String(eValue)){
								op.selected = true;
							}
						}
						break;
					case 'button': 
					case 'file': 
					case 'image': 
					case 'reset': 
					case 'submit': 
					default:  
				}
			}
		}
	}

	/**
	* invoke json data :
	* 1: a.bs[0].id
	* 2: a["bs"][0]["id"]
	* translate form into json
	*/
	JsonUtils.formToJsonObject = function(form){
		var jsonObject = {};
		for(i = 0, max = form.elements.length; i < max; i++) {
			e = form.elements[i];
			em = new Array();
			if(e.type == 'select-multiple'){
				for(j = 0; j < e.options.length; j++){
					op = e.options[j];
					if(op.selected){
						em[em.length] = op.value;
					}
				}
			}
			switch(e.type){
				case 'checkbox': 
				case 'radio': 
					if (!e.checked) { break; } 
				case 'hidden': 
				case 'password': 
				case 'select-one':
				case 'select-multiple':
				case 'textarea':
				case 'text': 
					eName = e.name;
					if(e.type == 'select-multiple'){
						eValue = em;
					}else{
						eValue = e.value;//.replace(new RegExp('(["\\\\])', 'g'), 'hjui$1');
					}
					//if eName has attributes
					if(eName.indexOf('.') > 0){
						dotIndex = eName.indexOf('.');
						parentName = eName.substring(0, dotIndex);
						childName = eName.substring(dotIndex+1);
						//handle eName`s attributes, pack them into jsonObject
						iterJsonObject(jsonObject, parentName, childName, eValue);
					}else{
						jsonObject[eName] = eValue;
					}
					break; 
				case 'button': 
				case 'file': 
				case 'image': 
				case 'reset': 
				case 'submit': 
				default:  
			}
		}
		return jsonObject;
	}

	JsonUtils.appendValue = function(jsonObject, eName, eValue){
		if(jsonObject){
			//if eName has attributes
			if(eName.indexOf('.') > 0){
				dotIndex = eName.indexOf('.');
				parentName = eName.substring(0, dotIndex);
				childName = eName.substring(dotIndex+1);
				//handle eName`s attributes, pack them into jsonObject
				iterJsonObject(jsonObject, parentName, childName, eValue);
			}else{
			//alert(" evalue : " + eValue);
				jsonObject[eName] = eValue;
			}
			//alert(JSON.stringify(jsonObject));
		}
	}
})();