<div class="grid gap-4 border p-4">
 @foreach ($files as $file)
  <div>
   {{ $file->name }}
  </div>
 @endforeach
</div>
