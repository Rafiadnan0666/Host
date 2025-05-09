@extends('layout.dash')

@section('konten')
<div class="container py-4">
    <h1 class="mb-4">News Management</h1>

    <!-- Search Bar -->
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search news...">

    <!-- Create News Button -->
    <button class="btn btn-primary mb-3" id="createNewsBtn">Create News</button>

    <!-- News Table -->
    <div class="table-responsive">
        <table class="table table-bordered" id="newsTable">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Approve</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($news as $item)
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->kategori->name ?? '-' }}</td>
                    <td>{{ $item->approve == 'y' ? 'Approved' : 'Not Approved' }}</td>
                    <td>
                        @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" width="80">
                        @else
                            No Image
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning editBtn" data-id="{{ $item->id }}">Edit</button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $item->id }}">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $news->links() }}
    </div>
</div>

<!-- Modal Create/Edit -->
<div class="modal fade" id="newsModal" tabindex="-1" aria-labelledby="newsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="newsForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" id="newsId">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newsModalLabel">Create News</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
              <label>Title</label>
              <input type="text" class="form-control" name="title" id="title">
          </div>

          <div class="mb-3">
              <label>Content</label>
              <textarea class="form-control" name="content" id="content" rows="3"></textarea>
          </div>

          <div class="mb-3">
              <label>Approve</label>
              <select class="form-control" name="approve" id="approve">
                  <option value="y">Yes</option>
                  <option value="n">No</option>
              </select>
          </div>

          <div class="mb-3">
              <label>Category</label>
              <select class="form-control" name="category_id" id="category_id">
                  @foreach($categories as $category)
                      <option class="text-color-black" value="{{ $category->id }}">{{ $category->name }}</option>
                  @endforeach
              </select>
          </div>

          <div class="mb-3">
              <label>Image</label>
              <input type="file" class="form-control" id="imageInput" name="image_file">
              <img id="previewImage" src="#" class="img-fluid mt-2 d-none" width="120">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
        </div>
      </div>
    </form>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search Bar
        document.getElementById('searchInput').addEventListener('input', function() {
            let value = this.value.toLowerCase();
            document.querySelectorAll('#newsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
            });
        });
    
        // Image Preview
        document.getElementById('imageInput').addEventListener('change', function(e) {
            let file = e.target.files[0];
            if (file) {
                let reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('previewImage').src = event.target.result;
                    document.getElementById('previewImage').classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    
        // Open Create Modal
        document.getElementById('createNewsBtn').addEventListener('click', function() {
            resetForm();
            document.getElementById('newsModalLabel').textContent = "Create News";
            new bootstrap.Modal(document.getElementById('newsModal')).show();
        });
    
        // Open Edit Modal
        document.querySelectorAll('.editBtn').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.dataset.id;
                fetch('/news/' + id + '/edit')
                    .then(response => response.json())
                    .then(data => {
                        resetForm();
                        document.getElementById('newsModalLabel').textContent = "Edit News";
                        document.getElementById('newsId').value = data.news.id;
                        document.getElementById('title').value = data.news.title;
                        document.getElementById('content').value = data.news.content;
                        document.getElementById('approve').value = data.news.approve;
                        document.getElementById('category_id').value = data.news.category_id;
                        if (data.news.image) {
                            document.getElementById('previewImage').src = '/storage/' + data.news.image;
                            document.getElementById('previewImage').classList.remove('d-none');
                        }
                        new bootstrap.Modal(document.getElementById('newsModal')).show();
                    });
            });
        });
    
        // Save Create or Update
        document.getElementById('newsForm').addEventListener('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
    
            let id = document.getElementById('newsId').value;
            let url = id ? '/news/update/' + id : '/news';
            let method = 'POST'; // Always POST
    
            fetch(url, {
                method: method,
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            })
            .catch(error => console.error(error));
        });
    
        // Delete News
        document.querySelectorAll('.deleteBtn').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to delete this news?')) {
                    let id = this.dataset.id;
                    fetch('/news/' + id, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        location.reload();
                    })
                    .catch(error => console.error(error));
                }
            });
        });
    
        // Reset Modal Form
        function resetForm() {
            document.getElementById('newsForm').reset();
            document.getElementById('newsId').value = '';
            document.getElementById('previewImage').classList.add('d-none');
        }
    });
    </script>
</div>



@endsection
