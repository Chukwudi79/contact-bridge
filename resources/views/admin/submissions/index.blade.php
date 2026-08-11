@extends('admin.layout')
@section('content')
<div class="page-head">
    <div><div class="eyebrow">Operations workspace</div><h1>Submissions</h1><div class="muted">Track and manage inbound contact messages across every website.</div></div>
    <form method="GET" class="actions"><select name="status"><option value="">All statuses</option>@foreach(['pending','sent','failed','in_progress','resolved'] as $option)<option value="{{ $option }}" @selected($status === $option)>{{ ucfirst(str_replace('_',' ',$option)) }}</option>@endforeach</select><button class="button" type="submit">Filter view</button></form>
</div>
<div class="card">
    <div class="table-scroll"><table><thead><tr><th>Received</th><th>Sender</th><th>Website / recipient</th><th>Message</th><th>Status</th><th>Update</th></tr></thead><tbody>
    @forelse($submissions as $submission)
    <tr><td class="muted">{{ $submission->created_at->format('d M Y, H:i') }}</td><td><strong>{{ $submission->first_name }} {{ $submission->last_name }}</strong><br><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></td><td>{{ $submission->website_origin }}<br><span class="muted">{{ $submission->recipient }}</span></td><td>{{ \Illuminate\Support\Str::limit($submission->message, 120) }}<br><span class="muted">{{ $submission->product ?: 'General inquiry' }}</span></td><td><span class="badge {{ $submission->status }}">{{ str_replace('_',' ', $submission->status) }}</span>@if($submission->failure_reason)<br><span class="muted">Delivery failed</span>@endif</td><td><div class="actions"><a class="button secondary" href="{{ route('admin.submissions.show', $submission) }}">View</a><form method="POST" action="{{ route('admin.submissions.update',$submission) }}" class="actions">@csrf @method('PATCH')<select name="status">@foreach(['pending','sent','failed','in_progress','resolved'] as $option)<option value="{{ $option }}" @selected($submission->status === $option)>{{ ucfirst(str_replace('_',' ',$option)) }}</option>@endforeach</select><button class="button" type="submit">Save</button></form></div></td></tr>
    @empty
    <tr><td colspan="6"><div class="empty"><div class="eyebrow">Clear runway</div><h2>No submissions yet</h2><div class="muted">New messages from approved website origins will appear here.</div></div></td></tr>
    @endforelse
    </tbody></table></div>
    @if($submissions->total())<div class="pagination"><span class="muted">Showing {{ $submissions->firstItem() }}-{{ $submissions->lastItem() }} of {{ $submissions->total() }}</span>@if($submissions->hasPages()){{ $submissions->links() }}@endif</div>@endif
</div>
@endsection
