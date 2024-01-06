<x-mail::message>

# Contact form
From {{ $name }} ({{ $email }})

<x-mail::panel>
# {{ $object }}
{{ $message }}
</x-mail::panel>

</x-mail::message>
