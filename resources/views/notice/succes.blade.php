<!-- Success -->
@if(\Session::has('success'))
    <div class="alert alert-success alert-block fade in">
        <button data-dismiss="alert" class="close close-sm" type="button">
            <i class="icon-remove"></i>
        </button>
        <h4>
            <i class="icon-ok-sign"></i>
            Success!
        </h4>
        <p>{{ \Session::get('success') }}</p>
    </div>
@endif