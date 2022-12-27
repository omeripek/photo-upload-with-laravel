
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Photos</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
<body class="antialiased">
    <div class="container-fluid text-center">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Upload</div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('photos.store') }}" id="uploadForm" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="photo">Images Files:</label>
                                <input type="file" name="photo" id="photo" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">Uploaded Pictures</div>
                    <div class="card-body">
                    <div class="photo-list m-2 row" id="photo-list">
                        @if (count($photos) > 0)
                            @foreach ($photos as $photo)
                            <div class="photo-box col" data-id="{{ $photo->id }}">
                                <img src="{{ asset($photo->path) }}" style="max-width:100px; max-heigth:100px;">
                                <form action="/my-photos/{{ $photo->id }}" method="POST">
                                @method('DELETE')    
                                @csrf
                                    <button type="submit" class="btn btn-danger btn-sm remove-button">Delete</button>
                                </form>
                            </div>
                            @endforeach
                        @else
                            <p>No uploaded pictures</p>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" integrity="sha256-lSjKY0/srUM9BE3dPm+c4fBo1dky2v27Gdjm2uoZaL0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.10.2/Sortable.min.js"></script>

    <script>
        // Photo adding
        $('#uploadForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: '{{ route('photos.store') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    // add list the photo
                    $('.photo-list').append(
                        '<div class="photo-box col" data-id="' + data.id + '">' +
                        '<img src="' + '{{ asset("") }}' + data.image +'" style="max-width:100px; max-heigth:100px;">' +

                        '<form action="/my-photos/' + data.id + '" method="delete">' +
                         '           @csrf ' +
                         '           <button type="submit" class="btn btn-danger btn-sm remove-button">Delete</button>' +
                         '       </form>' +
                        '</div>'
                    );
                    // success message
                },
                error: function(data) {
                    console.log(data);
                    // error message
                    alert(data.responseJSON.errors.photo[0]);
                }
            });
        });

        // deleting photo
        $('.photo-list').on('click', '.remove-button', function(e) {
            e.preventDefault();
            if (confirm('Are you sure for delete?')) {
                var photoBox = $(this).closest('.photo-box');
                var photoId = photoBox.data('id');

                $.ajax({
                    type: 'DELETE',
                    url: '{{ route('photos.destroy') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        photo:photoId
                    },
                    success: function(data) {
                        console.log(data);
                        // delete photo from list
                        photoBox.remove();
                        // success message
                        alert(data.success);
                    },
                    error: function(data) {
                        console.log(data);
                        // error
                        alert(data.responseJSON.message);
                    }
                });
            }
        });

        // Select the element that will be sortable
        var el = document.getElementById('photo-list');

        // Create the sortable instance
        var sortable = Sortable.create(el, {
        });

        sortable.on('sortable:stop', function(event) {
            // Get the list of sorted photos
            var sortedPhotos = event.to.children;

            // Update the order of the photos in the database
            for (var i = 0; i < sortedPhotos.length; i++) {
                var photoId = sortedPhotos[i].dataset.id;
                updatePhotoOrder(photoId, i + 1);
            }
        });

        function updatePhotoOrder(photoId, order) {
            // Send an AJAX request to update the photo order
            $.ajax({
                type: 'PATCH',
                url: '/my-photos/' + photoId,
                data: {
                    order: order
                },
                success: function(data) {
                    console.log(data);
                    // Show a success message
                    alert('Photo order updated successfully');
                },
                error: function(data) {
                    console.log(data);
                    // Show an error message
                    alert('An error occurred while updating the photo order');
                }
            });
        }

    </script>
</body>
</html>