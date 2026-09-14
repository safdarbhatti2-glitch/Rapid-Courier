<div style="padding: 1rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0b1830; margin: 0;">API & Webhook Platform Control Center</h1>
            <p style="color: #64748b; margin: 0.25rem 0 0 0; font-size: 0.85rem;">Global monitoring of external API client credentials, rate limits, webhook delivery logs, and audit logs.</p>
        </div>
        <div>
            <a href="<?= \App\Core\View::url('/docs/api/') ?>" target="_blank" class="btn btn-dark" style="text-decoration: none; padding: 0.6rem 1rem; font-size: 0.8rem; border-radius: 6px;">OpenAPI Specifications &rarr;</a>
        </div>
    </div>

    <!-- Active API Clients -->
    <div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 1.5rem; overflow: hidden;">
        <div style="padding: 1rem 1.25rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
            <h3 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: #1e293b;">Registered API Credentials (<?= count($apiKeys) ?>)</h3>
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.8rem;">
            <thead>
                <tr style="background: #f1f5f9; color: #475569;">
                    <th style="padding: 0.6rem 1rem;">Customer</th>
                    <th style="padding: 0.6rem 1rem;">App Name</th>
                    <th style="padding: 0.6rem 1rem;">API Key</th>
                    <th style="padding: 0.6rem 1rem;">Env</th>
                    <th style="padding: 0.6rem 1rem;">Rate Limit</th>
                    <th style="padding: 0.6rem 1rem;">Last Active</th>
                    <th style="padding: 0.6rem 1rem;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($apiKeys)): ?>
                    <tr><td colspan="7" style="padding: 1.5rem; text-align: center; color: #94a3b8;">No customer API credentials registered.</td></tr>
                <?php else: ?>
                    <?php foreach ($apiKeys as $k): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.6rem 1rem; font-weight: 700;"><?= e($k['company_name'] ?: $k['contact_name']) ?></td>
                            <td style="padding: 0.6rem 1rem;"><?= e($k['name']) ?></td>
                            <td style="padding: 0.6rem 1rem; font-family: monospace; color: #0284c7;"><?= e($k['api_key']) ?></td>
                            <td style="padding: 0.6rem 1rem;"><span style="background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-weight: 700; font-size: 0.7rem; text-transform: uppercase;"><?= e($k['environment']) ?></span></td>
                            <td style="padding: 0.6rem 1rem;"><?= (int)$k['rate_limit_rpm'] ?> RPM</td>
                            <td style="padding: 0.6rem 1rem; color: #64748b;"><?= $k['last_used_at'] ? date('M d, H:i', strtotime($k['last_used_at'])) : 'Never' ?></td>
                            <td style="padding: 0.6rem 1rem;">
                                <?php if ($k['status'] === 'active'): ?>
                                    <span style="color: #10b981; font-weight: 700;">● Active</span>
                                <?php else: ?>
                                    <span style="color: #ef4444; font-weight: 700;">● Revoked</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Webhook Deliveries -->
    <div style="grid-template-columns: 1fr 1fr; display: grid; gap: 1.5rem;">
        <div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden;">
            <div style="padding: 0.85rem 1rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <h3 style="margin: 0; font-size: 0.9rem; font-weight: 700; color: #1e293b;">Recent Webhook Deliveries</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.75rem;">
                <thead>
                    <tr style="background: #f1f5f9; color: #475569;">
                        <th style="padding: 0.5rem 0.75rem;">Event</th>
                        <th style="padding: 0.5rem 0.75rem;">URL</th>
                        <th style="padding: 0.5rem 0.75rem;">Code</th>
                        <th style="padding: 0.5rem 0.75rem;">Latency</th>
                        <th style="padding: 0.5rem 0.75rem;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($deliveries)): ?>
                        <tr><td colspan="5" style="padding: 1rem; text-align: center; color: #94a3b8;">No webhook dispatch logs recorded.</td></tr>
                    <?php else: ?>
                        <?php foreach ($deliveries as $d): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 0.5rem 0.75rem; font-weight: 700; color: #0f172a;"><?= e($d['event_type']) ?></td>
                                <td style="padding: 0.5rem 0.75rem; font-family: monospace; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= e($d['url']) ?></td>
                                <td style="padding: 0.5rem 0.75rem; font-weight: 700;"><?= (int)$d['response_code'] ?></td>
                                <td style="padding: 0.5rem 0.75rem; color: #64748b;"><?= (int)$d['execution_time_ms'] ?> ms</td>
                                <td style="padding: 0.5rem 0.75rem;">
                                    <?php if ($d['status'] === 'success'): ?>
                                        <span style="color: #16a34a; font-weight: 700;">OK</span>
                                    <?php else: ?>
                                        <span style="color: #dc2626; font-weight: 700;">FAILED</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden;">
            <div style="padding: 0.85rem 1rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <h3 style="margin: 0; font-size: 0.9rem; font-weight: 700; color: #1e293b;">Global API Traffic Logs</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.75rem;">
                <thead>
                    <tr style="background: #f1f5f9; color: #475569;">
                        <th style="padding: 0.5rem 0.75rem;">Method</th>
                        <th style="padding: 0.5rem 0.75rem;">Endpoint</th>
                        <th style="padding: 0.5rem 0.75rem;">Status</th>
                        <th style="padding: 0.5rem 0.75rem;">IP Address</th>
                        <th style="padding: 0.5rem 0.75rem;">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($auditLogs)): ?>
                        <tr><td colspan="5" style="padding: 1rem; text-align: center; color: #94a3b8;">No API audit logs recorded.</td></tr>
                    <?php else: ?>
                        <?php foreach (array_slice($auditLogs, 0, 15) as $al): ?>
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 0.5rem 0.75rem; font-weight: 700;"><?= e($al['method']) ?></td>
                                <td style="padding: 0.5rem 0.75rem; font-family: monospace;"><?= e($al['endpoint']) ?></td>
                                <td style="padding: 0.5rem 0.75rem;">
                                    <?php if ($al['response_code'] < 300): ?>
                                        <span style="color: #16a34a; font-weight: 700;"><?= (int)$al['response_code'] ?></span>
                                    <?php else: ?>
                                        <span style="color: #dc2626; font-weight: 700;"><?= (int)$al['response_code'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 0.5rem 0.75rem; color: #64748b;"><?= e($al['ip_address']) ?></td>
                                <td style="padding: 0.5rem 0.75rem; color: #64748b;"><?= date('H:i:s', strtotime($al['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
