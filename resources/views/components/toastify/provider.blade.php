<div
 hidden
 aria-hidden="true"
 x-init
 x-on:notifications.window="(ev) => {
  let notifications =ev.detail;
  if(!notifications) return;

  if (!Array.isArray(notifications)) {
      notifications = [notifications];
  }
  if(notifications.length) {
    $nextTick(() => {
      notifications.map((notification) => {
        console.log('description', notification.description)
        $toastify({...notification})
      })
    });
  }
 }"
>
 Notification Listeners
</div>

@if (@$errors?->any())
 <div
  class="sr-only"
  x-data="{
      errors: {{ json_encode($errors->all()) }}
  }"
  x-effect="() => {
    if(errors.length > 0) {
      errors.map(error => {
        $toastify({
          type: 'danger',
          duration: 30000,
          message: error,
        })
      })
    }
  }"
 >
  Erorr Message
 </div>
@endif

@if (session('notifications'))
 <div x-init="() => {
     let notifications = @js(session('notifications'));
     if (!Array.isArray(notifications)) {
         notifications = [notifications];
     }
 
     if (notifications.length) {
         $nextTick(() => {
             notifications.map((notification) => {
                 $toastify({ ...notification })
             })
         });
     }
 }">
 </div>
@endif
