<?php if(strval($section->parametrs->param38)!='d' && strval($section->parametrs->param38)!=''): ?>
<div class="<?php if(strval($section->parametrs->param38)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>"><?php endif; ?>
<section class="content contOnNews view" id="view"> 
    <div class="contentBody">
        <a class="backLink" href="<?php echo $__data->getLinkPageName() ?>"><?php echo $section->language->lang012 ?></a> 
        <h1 class="objectTitle">
            <span class="objectTitleTxt"><?php echo $news_title ?></span>
        </h1>
        <div id="objimage"> 
            <?php echo $news_img ?>
        </div>
        <div class="objectText">
            <?php echo $news_text ?>
        </div>
    </div>
    <?php if(strval($section->parametrs->param31)=='Y'): ?>
        
            <header:js>[js:jquery/jquery.min.js]</header:js>
            <script type="text/javascript" src="http://yandex.st/share/share.js" charset="utf-8"></script>
            <script type="text/javascript">
                new Ya.share({
                    element: 'ya_share1',
                    elementStyle: {type: 'button', linkIcon: true, border: false, quickServices: ['facebook', 'twitter', 'vkontakte', 'moimir', 'yaru', 'odnoklassniki', 'lj'] },
                    popupStyle: {'copyPasteField': true},
                    onready: function(ins){
                        $(ins._block).find(".b-share").append("<a href=\"http://siteedit.ru?rf=<?php echo $section->parametrs->param34 ?>\" class=\"b-share__handle b-share__link\" title=\"SiteEdit\" target=\"_blank\"  rel=\"nofollow\"><span class=\"b-share-icon\" style=\"background:url('http://siteedit.ru/se/skin/siteedit_icon_16x16.png') no-repeat;\"></span></a>");                    
                    }
                });
            </script>
        
        <div id="ya_share1" style="margin: 10px 0;">
            
        </div>
    <?php endif; ?>
    <input class="buttonSend" onclick="window.history.back(-1);" type="button" value="<?php echo $section->language->lang013 ?>">
</section><?php if(strval($section->parametrs->param38)!='d' && strval($section->parametrs->param38)!=''): ?>
</div><?php endif; ?>
