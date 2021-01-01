/**
 * Create HTML Editor (for PHPMaker 2021)
 * @license (C) 2020 e.World Technology Ltd.
 */
CKEDITOR.env.cssClass="ew-editor",ew.createEditor=function(e,t,n,a,i){if("undefined"!=typeof CKEDITOR&&!t.includes("$rowindex$")){var s=jQuery,o=s("#"+e)[0],r=ew.getElement(t,o);if(r){n&&Math.abs(n);var c=1.5*((a?Math.abs(a):4)+4)+"em",d=(ew.LANGUAGE_ID||"").toLowerCase();"zh-hk"!=d&&"zh-tw"!=d&&"de-at"!=d&&"pt-pt"!=d&&"es-419"!=d||(d=d.substring(0,2));var u={id:t,form:o,enabled:!0,settings:{height:c,language:d,autoUpdateElement:!1,baseHref:""}};if(s(document).trigger("create.editor",[u]),u.enabled){i&&(u.settings.readOnly=!0,u.settings.toolbar=[["Source"]]);var l={name:t,active:!1,instance:null,create:function(){(this.instance=CKEDITOR.replace(r,u.settings)).on("loaded",ew.fixLayoutHeight),this.active=!0},set:function(){this.instance&&this.instance.setData(this.instance.element.value)},save:function(){this.instance&&this.instance.updateElement();var e={id:t,form:o,value:ew.removeSpaces(r.value)};s(document).trigger("save.editor",[e]).val(e.value)},focus:function(){this.instance&&this.instance.focus()},destroy:function(){this.instance&&this.instance.destroy()}};s(r).data("editor",l).addClass("editor")}}}};