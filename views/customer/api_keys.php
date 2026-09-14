<div style="padding: 1.5rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0;">API Management & Credentials</h1>
            <p style="color: #64748b; margin: 0; font-size: 0.9rem;">Generate scoped API keys, rotate credentials, manage webhooks, and inspect real-time audit logs for RC Courier REST API v1.</p>
        </div>
        <div>
            <a href="<?= \App\Core\View::url('/docs/api/') ?>" target="_blank" class="btn btn-dark" style="text-decoration: none; padding: 0.6rem 1.2rem; font-size: 0.85rem; border-radius: 8px;">View API Specs &rarr;</a>
        </div>
    </div>

    <!-- Usage Statistics KPI Bar -->
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem;">
        <div style="background: #ffffff; border-radius: 12px; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.3rem;">Total API Calls</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #0f172a;"><?= number_format($stats['total_requests'] ?? 0) ?></div>
        </div>
        <div style="background: #ffffff; border-radius: 12px; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="font-size: 0.8rem; font-weight: 700; color: #16a34a; text-transform: uppercase; margin-bottom: 0.3rem;">Successful (2xx)</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #15803d;"><?= number_format($stats['success_requests'] ?? 0) ?></div>
        </div>
        <div style="background: #ffffff; border-radius: 12px; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="font-size: 0.8rem; font-weight: 700; color: #dc2626; text-transform: uppercase; margin-bottom: 0.3rem;">Errors (4xx / 5xx)</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #b91c1c;"><?= number_format($stats['error_requests'] ?? 0) ?></div>
        </div>
        <div style="background: #ffffff; border-radius: 12px; padding: 1.25rem; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div style="font-size: 0.8rem; font-weight: 700; color: #0284c7; text-transform: uppercase; margin-bottom: 0.3rem;">Active Credentials</div>
            <div style="font-size: 1.6rem; font-weight: 800; color: #0369a1;"><?= number_format($stats['active_keys'] ?? 0) ?></div>
        </div>
    </div>

    <?php $newCred = \App\Core\Session::getFlash('new_api_credential'); ?>
    <?php if ($newCred): ?>
        <div style="background: #ecfdf5; border: 1.5px solid #10b981; border-radius: 12px; padding: 1.25rem; margin-bottom: 2rem;">
            <h3 style="color: #065f46; margin: 0 0 0.5rem 0; font-size: 1.1rem;">API Key Generated Successfully!</h3>
            <p style="color: #047857; margin: 0 0 1rem 0; font-size: 0.85rem;">Make sure to copy your API Secret now. You will not be able to see it again!</p>
            <div style="background: #ffffff; padding: 1rem; border-radius: 8px; font-family: monospace; font-size: 0.85rem; border: 1px solid #a7f3d0;">
                <div><strong>API Key (X-API-Key):</strong> <code><?= e($newCred['api_key']) ?></code></div>
                <div style="margin-top: 0.5rem; color: #b91c1c;"><strong>API Secret (X-API-Secret):</strong> <code><?= e($newCred['api_secret']) ?></code></div>
                <div style="margin-top: 0.5rem; color: #4b5563;"><strong>Environment:</strong> <code><?= e($newCred['environment']) ?></code></div>
                <div style="margin-top: 0.5rem; color: #4b5563;"><strong>Permissions:</strong> <?= implode(', ', $newCred['permissions'] ?? []) ?></div>
            </div>
        </div>
    <?php endif; ?>

    <div style="grid-template-columns: 1fr 1fr; display: grid; gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Generate New Scoped Key Form -->
        <div style="background: #ffffff; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <h3 style="margin: 0 0 1rem 0; font-size: 1.1rem; font-weight: 700; color: #1e293b;">Generate Scoped API Key</h3>
            <form action="<?= \App\Core\View::url('/customer/api-keys/create') ?>" method="POST">
                <?= csrf_field() ?>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Application Name</label>
                    <input type="text" name="name" required placeholder="e.g. ERP Integration / Mobile App" style="width: 100%; padding: 0.65rem; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.9rem;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Environment</label>
                    <select name="environment" style="width: 100%; padding: 0.65rem; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.9rem;">
                        <option value="live">Live (Production Network)</option>
                        <option value="test">Test (Sandbox Environment)</option>
                    </select>
                </div>
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 0.5rem;">API Permission Scopes</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; background: #f8fafc; padding: 0.75rem; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.8rem;">
                        <label><input type="checkbox" name="permissions[]" value="shipments:create" checked> shipments:create</label>
                        <label><input type="checkbox" name="permissions[]" value="shipments:read" checked> shipments:read</label>
                        <label><input type="checkbox" name="permissions[]" value="shipments:cancel" checked> shipments:cancel</label>
                        <label><input type="checkbox" name="permissions[]" value="quotes:create" checked> quotes:create</label>
                        <label><input type="checkbox" name="permissions[]" value="tracking:read" checked> tracking:read</label>
                        <label><input type="checkbox" name="permissions[]" value="invoices:read" checked> invoices:read</label>
                        <label><input type="checkbox" name="permissions[]" value="labels:read" checked> labels:read</label>
                        <label><input type="checkbox" name="permissions[]" value="webhooks:manage" checked> webhooks:manage</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-gold" style="width: 100%; padding: 0.75rem; font-weight: 700; border-radius: 8px;">Generate Credentials</button>
            </form>
        </div>

        <!-- Quick API Specs & Webhook Register -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div style="background: #0f172a; color: #f8fafc; border-radius: 12px; padding: 1.5rem; flex: 1;">
                <h3 style="margin: 0 0 0.75rem 0; font-size: 1.1rem; font-weight: 700; color: #f1c45e;">API Header Authentication</h3>
                <p style="font-size: 0.85rem; color: #94a3b8; margin: 0 0 1rem 0;">Provide Bearer header or X-API Key/Secret headers on every REST call:</p>
                <pre style="background: #1e293b; padding: 0.85rem; border-radius: 8px; font-size: 0.8rem; color: #38bdf8; overflow-x: auto; margin: 0;">Authorization: Bearer rc_live_...:sec_...
X-API-Key: rc_live_7f8a9b0c...
X-API-Secret: sec_a1b2c3d4...
Idempotency-Key: IDEMP-20260914-8871</pre>
            </div>

            <!-- Register Webhook Form -->
            <div style="background: #ffffff; border-radius: 12px; padding: 1.25rem; border: 1px solid #e2e8f0;">
                <h3 style="margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 700; color: #1e293b;">Register Webhook Endpoint</h3>
                <form action="<?= \App\Core\View::url('/customer/webhooks/create') ?>" method="POST">
                    <?= csrf_field() ?>
                    <div style="margin-bottom: 0.75rem;">
                        <input type="url" name="url" required placeholder="https://example.com/api/webhooks/rc-courier" style="width: 100%; padding: 0.6rem; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.85rem;">
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.75rem; color: #64748b;">Subscribed to status events</span>
                        <button type="submit" class="btn btn-dark" style="padding: 0.5rem 1rem; font-size: 0.8rem; border-radius: 6px;">Register Webhook</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Active API Keys Table -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 2rem;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
            <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #1e293b;">Active API Keys</h3>
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
            <thead>
                <tr style="background: #f1f5f9; color: #475569;">
                    <th style="padding: 0.75rem 1rem;">Application Name</th>
                    <th style="padding: 0.75rem 1rem;">API Key</th>
                    <th style="padding: 0.75rem 1rem;">Env</th>
                    <th style="padding: 0.75rem 1rem;">Scopes</th>
                    <th style="padding: 0.75rem 1rem;">Last Used</th>
                    <th style="padding: 0.75rem 1rem;">Status</th>
                    <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($keys)): ?>
                    <tr><td colspan="7" style="padding: 2rem; text-align: center; color: #94a3b8;">No API keys generated yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($keys as $k): ?>
                        <?php $permsList = !empty($k['permissions']) ? json_decode($k['permissions'], true) : []; ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 1rem; font-weight: 700;"><?= e($k['name']) ?></td>
                            <td style="padding: 0.75rem 1rem; font-family: monospace; font-size: 0.8rem;"><?= e($k['api_key']) ?></td>
                            <td style="padding: 0.75rem 1rem;"><span style="background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase; font-size: 0.75rem;"><?= e($k['environment']) ?></span></td>
                            <td style="padding: 0.75rem 1rem;">
                                <?php foreach (array_slice($permsList, 0, 3) as $p): ?>
                                    <span style="background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569; padding: 1px 6px; border-radius: 4px; font-size: 0.7rem; margin-right: 2px;"><?= e($p) ?></span>
                                <?php endforeach; ?>
                                <?php if (count($permsList) > 3): ?>
                                    <span style="color: #64748b; font-size: 0.7rem;">+<?= count($permsList) - 3 ?> more</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 0.75rem 1rem; color: #64748b; font-size: 0.8rem;">
                                <?= !empty($k['last_used_at']) ? date('M d, H:i', strtotime($k['last_used_at'])) : '<span style="color:#94a3b8;">Never</span>' ?>
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                <?php if ($k['status'] === 'active'): ?>
                                    <span style="color: #10b981; font-weight: 700;">● Active</span>
                                <?php else: ?>
                                    <span style="color: #ef4444; font-weight: 700;">● Revoked</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: right;">
                                <?php if ($k['status'] === 'active'): ?>
                                    <form action="<?= \App\Core\View::url('/customer/api-keys/rotate') ?>" method="POST" style="display: inline; margin-right: 0.5rem;" onsubmit="return confirm('Rotate this API Key? The old key will be revoked immediately and a new key generated.');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="key_id" value="<?= $k['id'] ?>">
                                        <button type="submit" style="background: none; border: none; color: #0284c7; font-weight: 700; cursor: pointer; text-decoration: underline; font-size: 0.8rem;">Rotate</button>
                                    </form>
                                    <form action="<?= \App\Core\View::url('/customer/api-keys/revoke') ?>" method="POST" style="display: inline;" onsubmit="return confirm('Revoke this API Key immediately?');">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="key_id" value="<?= $k['id'] ?>">
                                        <button type="submit" style="background: none; border: none; color: #ef4444; font-weight: 700; cursor: pointer; text-decoration: underline; font-size: 0.8rem;">Revoke</button>
                                    </form>
                                <?php else: ?>
                                    <span style="color: #94a3b8;">Disabled</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Webhooks Table -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 2rem;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
            <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #1e293b;">Registered Webhooks</h3>
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.85rem;">
            <thead>
                <tr style="background: #f1f5f9; color: #475569;">
                    <th style="padding: 0.75rem 1rem;">Webhook URL</th>
                    <th style="padding: 0.75rem 1rem;">HMAC Secret</th>
                    <th style="padding: 0.75rem 1rem;">Events</th>
                    <th style="padding: 0.75rem 1rem;">Status</th>
                    <th style="padding: 0.75rem 1rem; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($webhooks)): ?>
                    <tr><td colspan="5" style="padding: 1.5rem; text-align: center; color: #94a3b8;">No webhooks registered yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($webhooks as $wh): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 1rem; font-family: monospace; font-size: 0.8rem; color: #0f172a;"><?= e($wh['url']) ?></td>
                            <td style="padding: 0.75rem 1rem; font-family: monospace; font-size: 0.75rem; color: #64748b;"><?= e(substr($wh['secret'], 0, 14)) ?>...</td>
                            <td style="padding: 0.75rem 1rem;">
                                <span style="background: #f1f5f9; color: #475569; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">shipment.*</span>
                            </td>
                            <td style="padding: 0.75rem 1rem;">
                                <?php if ($wh['status'] === 'active'): ?>
                                    <span style="color: #10b981; font-weight: 700;">● Active</span>
                                <?php else: ?>
                                    <span style="color: #ef4444; font-weight: 700;">● Disabled</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 0.75rem 1rem; text-align: right;">
                                <form action="<?= \App\Core\View::url('/customer/webhooks/test') ?>" method="POST" style="display: inline; margin-right: 0.4rem;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="webhook_id" value="<?= $wh['id'] ?>">
                                    <button type="submit" class="btn btn-outline" style="padding: 2px 8px; font-size: 0.75rem; border-radius: 4px;">Test Ping</button>
                                </form>
                                <form action="<?= \App\Core\View::url('/customer/webhooks/toggle') ?>" method="POST" style="display: inline; margin-right: 0.4rem;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="webhook_id" value="<?= $wh['id'] ?>">
                                    <button type="submit" style="background: none; border: none; color: #0284c7; cursor: pointer; text-decoration: underline; font-size: 0.75rem; font-weight: 700;"><?= $wh['status'] === 'active' ? 'Disable' : 'Enable' ?></button>
                                </form>
                                <form action="<?= \App\Core\View::url('/customer/webhooks/delete') ?>" method="POST" style="display: inline;" onsubmit="return confirm('Delete this webhook endpoint?');">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="webhook_id" value="<?= $wh['id'] ?>">
                                    <button type="submit" style="background: none; border: none; color: #ef4444; cursor: pointer; text-decoration: underline; font-size: 0.75rem; font-weight: 700;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Webhook Deliveries & Retries Inspector -->
    <?php if (!empty($deliveries)): ?>
        <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 2rem;">
            <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
                <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #1e293b;">Recent Webhook Deliveries & Retries</h3>
            </div>
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.8rem;">
                <thead>
                    <tr style="background: #f1f5f9; color: #475569;">
                        <th style="padding: 0.6rem 1rem;">Event ID</th>
                        <th style="padding: 0.6rem 1rem;">Event Type</th>
                        <th style="padding: 0.6rem 1rem;">Target URL</th>
                        <th style="padding: 0.6rem 1rem;">HTTP Code</th>
                        <th style="padding: 0.6rem 1rem;">Attempt #</th>
                        <th style="padding: 0.6rem 1rem;">Status</th>
                        <th style="padding: 0.6rem 1rem;">Time</th>
                        <th style="padding: 0.6rem 1rem; text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($deliveries as $del): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.6rem 1rem; font-family: monospace; color: #0284c7;"><?= e($del['event_id']) ?></td>
                            <td style="padding: 0.6rem 1rem; font-weight: 700;"><?= e($del['event_type']) ?></td>
                            <td style="padding: 0.6rem 1rem; font-family: monospace; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?= e($del['webhook_url']) ?></td>
                            <td style="padding: 0.6rem 1rem;">
                                <?php if ($del['response_code'] >= 200 && $del['response_code'] < 300): ?>
                                    <span style="background: #dcfce7; color: #15803d; padding: 2px 6px; border-radius: 4px; font-weight: 700;"><?= (int)$del['response_code'] ?></span>
                                <?php else: ?>
                                    <span style="background: #fee2e2; color: #b91c1c; padding: 2px 6px; border-radius: 4px; font-weight: 700;"><?= (int)$del['response_code'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 0.6rem 1rem; font-weight: 700; text-align: center;"><?= (int)$del['attempt'] ?></td>
                            <td style="padding: 0.6rem 1rem;">
                                <?php if ($del['status'] === 'success'): ?>
                                    <span style="color: #15803d; font-weight: 700;">Success</span>
                                <?php else: ?>
                                    <span style="color: #b91c1c; font-weight: 700;">Failed</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 0.6rem 1rem; color: #64748b;"><?= date('M d, H:i:s', strtotime($del['created_at'])) ?></td>
                            <td style="padding: 0.6rem 1rem; text-align: right;">
                                <form action="<?= \App\Core\View::url('/customer/webhooks/retry') ?>" method="POST" style="display: inline;">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="delivery_id" value="<?= $del['id'] ?>">
                                    <button type="submit" style="background: none; border: none; color: #0284c7; cursor: pointer; text-decoration: underline; font-size: 0.75rem; font-weight: 700;">Retry Delivery</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- API Audit Logs Table -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
            <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #1e293b;">Recent API Request Audit Logs</h3>
        </div>
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.8rem;">
            <thead>
                <tr style="background: #f1f5f9; color: #475569;">
                    <th style="padding: 0.6rem 1rem;">Request ID</th>
                    <th style="padding: 0.6rem 1rem;">Method</th>
                    <th style="padding: 0.6rem 1rem;">Endpoint</th>
                    <th style="padding: 0.6rem 1rem;">Status Code</th>
                    <th style="padding: 0.6rem 1rem;">Latency</th>
                    <th style="padding: 0.6rem 1rem;">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr><td colspan="6" style="padding: 1.5rem; text-align: center; color: #94a3b8;">No API requests logged yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($logs as $l): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.6rem 1rem; font-family: monospace; color: #0284c7;"><?= e($l['request_id']) ?></td>
                            <td style="padding: 0.6rem 1rem; font-weight: 700;"><?= e($l['method']) ?></td>
                            <td style="padding: 0.6rem 1rem; font-family: monospace;"><?= e($l['endpoint']) ?></td>
                            <td style="padding: 0.6rem 1rem;">
                                <?php if ($l['response_code'] < 300): ?>
                                    <span style="background: #dcfce7; color: #15803d; padding: 2px 6px; border-radius: 4px; font-weight: 700;"><?= (int)$l['response_code'] ?></span>
                                <?php else: ?>
                                    <span style="background: #fee2e2; color: #b91c1c; padding: 2px 6px; border-radius: 4px; font-weight: 700;"><?= (int)$l['response_code'] ?></span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 0.6rem 1rem; color: #64748b;"><?= (int)$l['execution_time_ms'] ?> ms</td>
                            <td style="padding: 0.6rem 1rem; color: #64748b;"><?= date('M d, H:i:s', strtotime($l['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
