var s_index = {
	init: function() {
          this.textEditorsInit();
	},
        
        textEditorsInit: function(){
//            new TINY.editor.edit('editor',{
//                                                id:'input',
//                                                width:800,
//                                                height:175,
//                                                cssclass:'te',
//                                                controlclass:'tecontrol',
//                                                rowclass:'teheader',
//                                                dividerclass:'tedivider',
//                                                controls:['bold','italic','underline','strikethrough','|','subscript','superscript','|',
//                                                                  'orderedlist','unorderedlist','|','outdent','indent','|','leftalign',
//                                                                  'centeralign','rightalign','blockjustify','|','unformat','|','undo','redo','n',
//                                                                  'font','size','style','|','image','hr','link','unlink','|','cut','copy','paste','print'],
//                                                footer:true,
//                                                fonts:['Verdana','Arial','Georgia','Trebuchet MS'],
//                                                xhtml:true,
//                                                cssfile:SYS.baseUrl + 'css/editor/style.css',
//                                                bodyid:'editor',
//                                                footerclass:'tefooter',
//                                                toggle:{text:'source',activetext:'wysiwyg',cssclass:'toggle'},
//                                                resize:{cssclass:'resize'}
//                                        });
        }
}

$(function() {
    s_index.init();
});