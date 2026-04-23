<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    /**
     * Export all clients data as CSV
     */
    public function exportClientsCSV()
    {
        $clients = User::where('is_admin', false)->get(['id', 'name', 'email', 'account_type', 'is_verified_client', 'created_at', 'updated_at']);

        $filename = 'clients_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($clients) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Account Type', 'Verified', 'Created At', 'Updated At']);
            
            // Add client data
            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->id,
                    $client->name,
                    $client->email,
                    $client->account_type,
                    $client->is_verified_client ? 'Yes' : 'No',
                    $client->created_at->format('Y-m-d H:i:s'),
                    $client->updated_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    /**
     * Export clients data as Excel (XLSX)
     */
    public function exportClientsExcel()
    {
        $clients = User::where('is_admin', false)->get(['id', 'name', 'email', 'account_type', 'is_verified_client', 'created_at', 'updated_at'])->toArray();

        $filename = 'clients_' . date('Y-m-d_H-i-s') . '.xlsx';

        // Create worksheet HTML that Excel can read
        $html = '<table>';
        $html .= '<tr><th>ID</th><th>Name</th><th>Email</th><th>Account Type</th><th>Verified</th><th>Created At</th><th>Updated At</th></tr>';
        
        foreach ($clients as $client) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($client['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($client['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($client['email']) . '</td>';
            $html .= '<td>' . htmlspecialchars($client['account_type']) . '</td>';
            $html .= '<td>' . ($client['is_verified_client'] ? 'Yes' : 'No') . '</td>';
            $html .= '<td>' . $client['created_at'] . '</td>';
            $html .= '<td>' . $client['updated_at'] . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';

        // Excel format as HTML
        $content = <<<EOT
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table { border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
    </style>
</head>
<body>
$html
</body>
</html>
EOT;

        return response($content, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Export clients data as PDF
     */
    public function exportClientsPDF()
    {
        $clients = User::where('is_admin', false)->get(['id', 'name', 'email', 'account_type', 'is_verified_client', 'created_at', 'updated_at']);

        $filename = 'clients_' . date('Y-m-d_H-i-s') . '.pdf';

        // Generate PDF HTML
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                h1 { color: #333; }
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
                th { background-color: #4CAF50; color: white; }
                tr:nth-child(even) { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <h1>Client Data Export</h1>
            <p>Generated on: ' . date('Y-m-d H:i:s') . '</p>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Account Type</th>
                    <th>Verified</th>
                    <th>Created At</th>
                </tr>';

        foreach ($clients as $client) {
            $html .= '
                <tr>
                    <td>' . $client->id . '</td>
                    <td>' . htmlspecialchars($client->name) . '</td>
                    <td>' . htmlspecialchars($client->email) . '</td>
                    <td>' . $client->account_type . '</td>
                    <td>' . ($client->is_verified_client ? 'Yes' : 'No') . '</td>
                    <td>' . $client->created_at->format('Y-m-d H:i:s') . '</td>
                </tr>';
        }

        $html .= '
            </table>
        </body>
        </html>';

        // Use DOMPDF or simple PDF alternative
        // For now, returning as downloadable HTML that can be printed to PDF
        return response($html, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    /**
     * Get clients list (JSON for AJAX)
     */
    public function getClients()
    {
        $clients = User::where('is_admin', false)
            ->select('id', 'name', 'email', 'account_type', 'is_verified_client', 'created_at')
            ->paginate(20);

        return response()->json($clients);
    }

    /**
     * Show team management page
     */
    public function showTeam()
    {
        // Authorization check
        if (!auth()->user()->isCeo() && !auth()->user()->hasPermission('manage_team')) {
            abort(403);
        }

        return view('admin.team');
    }

    /**
     * Deactivate team member
     */
    public function deactivateTeamMember(User $user)
    {
        // Authorization check
        if (!auth()->user()->isCeo() && !auth()->user()->hasPermission('manage_team')) {
            abort(403);
        }

        // Check role hierarchy
        $currentUser = auth()->user();
        if (!$currentUser->isCEO() && $user->isCEO()) {
            abort(403);
        }

        if ($currentUser->isAdmin() && !$user->isEmployee()) {
            abort(403);
        }

        $user->update([
            'status' => 'inactive',
            'deactivated_at' => now(),
        ]);

        return redirect()->route('admin.team')->with('success', $user->name . ' has been deactivated.');
    }

    /**
     * Resend team invitation
     */
    public function resendTeamInvitation(User $user)
    {
        // Authorization check
        if (!auth()->user()->isCeo() && !auth()->user()->hasPermission('manage_team')) {
            abort(403);
        }

        if ($user->status !== 'invited') {
            return redirect()->route('admin.team')->with('error', 'Can only resend invitations to invited members.');
        }

        // TODO: Resend invitation email
        // Mail::send(new TeamInvitationMail($user, $user->invitation_token));

        $user->update(['invitation_sent_at' => now()]);

        return redirect()->route('admin.team')->with('success', 'Invitation resent to ' . $user->email);
    }
}
