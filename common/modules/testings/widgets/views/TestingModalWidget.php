<!-- .modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="/images/testing/modal_close_icon.png" height="25" width="26" alt=""></button>
                <h4 class="modal-title" id="myLargeModalLabel">Зачем проходить тестирование?</h4>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-sm-12">

                        <?=Setting::getValue('testing_modal_widget_content');?>


                        <div class="center_green_button">
                            <a href="" class="green_button close" data-dismiss="modal" aria-label="Close">Закрыть</a>
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /.modal -->