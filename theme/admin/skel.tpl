<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Admin</title>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
        <meta name="format-detection" content="telephone=no">
        {*<link href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />*}
        <link href="/design/css/admin.css" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="//code.jquery.com/jquery-3.1.0.min.js"></script>
        {*<script type="text/javascript" src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>*}
        {literal}
            <script type="text/javascript">
                $(document).ready(function()
                {
                    if($("#Mainframe").length)
                        $("#Mainframe").height($(".LeftCell").height());

                    $(".ChangeLocation").click(function()
                    {
                        $("#Mainframe").attr('src', $(this).attr('href'));
                        return false;
                    });

                    $(".ChangeEditFrame").click(function()
                    {
                        $("#Editframe").attr('src', $(this).attr('href'));
                        return false;
                    });
                });
            </script>
        {/literal}
    </head>
    <body>
        {*<div class="Full Hidden">*}
            {block name=skel_data}{/block}
        {*</div>*}
    </body>
</html>