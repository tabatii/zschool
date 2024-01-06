<x-layouts.pdf>
    <div style="padding-top: 5rem">
        <div style="text-align: center;">
            <span style="font-size: 3rem; line-height: 1; color: #364f94;">RESULTS</span>
        </div>
        <div style="background-color: #82b39c; height: 1px; margin: 2rem 0;"></div>
        <div style="margin-bottom: 2rem;">
            <div style="margin-bottom: 0.5rem">
                <small>SCHOOL NAME :</small>
                <small>{{ $school }}</small>
            </div>
            <div style="margin-bottom: 0.5rem">
                <small>STUDENT NAME :</small>
                <small>{{ $student->name }}</small>
            </div>
            <div style="margin-bottom: 0.5rem">
                <small>SEASON :</small>
                <small>{{ $group->season->name }}</small>
            </div>
            <div>
                <small>GROUP :</small>
                <small>{{ $group->name }}</small>
            </div>
        </div>
        <table style="width: 100%; margin-bottom: 4rem; border-collapse: collapse;">
            <tbody>
                <tr>
                    <td colspan="3"></td>
                </tr>
                <tr style="text-align: center; color: #364f94;">
                    <td style="background-color: #e6efea; border: 1px solid #82b39c; border-bottom-width: 3px; padding: 0.25rem 1rem;">
                        <small>SUBJECT</small>
                    </td>
                    <td style="background-color: #e6efea; border: 1px solid #82b39c; border-bottom-width: 3px; padding: 0.25rem 1rem;">
                        <small>RESULT</small>
                    </td>
                    <td style="background-color: #e6efea; border: 1px solid #82b39c; border-bottom-width: 3px; padding: 0.25rem 1rem;">
                        <small>NOTE</small>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 1rem;" colspan="3"></td>
                </tr>
                @foreach ($group->sessions as $session)
                    <tr>
                        <td style="border: 1px solid #82b39c; padding: 0.25rem 1rem;">
                            <small>{{ $session->subject->name }}</small>
                        </td>
                        <td style="border: 1px solid #82b39c; padding: 0.25rem 1rem;">
                            <small>{{ $session->exam->students->firstWhere('id', $student->id)?->pivot->result }}</small>
                        </td>
                        <td style="border: 1px solid #82b39c; padding: 0.25rem 1rem;">
                            <small>{{ $session->exam->students->firstWhere('id', $student->id)?->pivot->note }}</small>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td style="height: 5rem; padding: 0.25rem 1rem; vertical-align: bottom;" colspan="2"></td>
                    <td style="border-right: 1px solid #82b39c; height: 5rem; padding: 0.25rem 1rem; vertical-align: bottom;">
                        <small>TOTAL MARKS</small>
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="text-align: center;">
            <img src="{{ asset('assets/logo.png') }}" style="display: inline-block; height: 5rem" />
        </div>
    </div>
</x-layouts.pdf>