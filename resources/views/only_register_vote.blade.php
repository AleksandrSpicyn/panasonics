<div class="modal fade front-modal" id="VoteModal" tabindex="-1" role="dialog" aria-labelledby="VoteModal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content" id="reset">
            <div class="modal-header">
                <a class="close-modal" data-dismiss="modal" aria-label="Close">
                    <img src="/assets/img/cross.png" alt="">
                </a>
            </div>
            <div class="modal-body text-center">
                <p class="font-weight-500">{{__('app.only_register_vote')}}</p>
                <a id="changeFormVoteToAuth"
                   class="modal-submit modal-auth font-weight-300 text-center text-uppercase d-block">{{__('app.entry')}}</a>
                <a class="modal-register font-weight-300 text-center text-uppercase d-block register_button"
                   id="changeFormVoteToRegister">{{__('app.in_register')}}</a>
            </div>
        </div>
    </div>
</div>
