<div style="padding: 1.5rem 0;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <div>
            <h1 style="font-size: 1.75rem; font-weight: 800; color: #0f172a; margin: 0 0 0.25rem 0;">API Management & Credentials</h1>
            <p style="color: #64748b; margin: 0; font-size: 0.9rem;">Generate API keys, register webhook endpoints, and inspect integration logs for RC Courier REST API v1.</p>
        </div>
        <div>
            <a href="<?= \App\Core\View::url('/docs/api/') ?>" target="_blank" class="btn btn-dark" style="text-decoration: none; padding: 0.6rem 1.2rem; font-size: 0.85rem; border-radius: 8px;">View API Docs &rarr;</a>
        </div>
    </div>

    <?php $newCred = \App\Core\Session::getFlash('new_api_credential'); ?>
    <?php if ($newCred): ?>
        <div style="background: #ecfdf5; border: 1.5px solid #10b981; border-radius: 12px; padding: 1.25rem; margin-bottom: 2rem;">
            <h3 style="color: #065f46; margin: 0 0 0.5rem 0; font-size: 1.1rem;">New API Credential Generated Successfully!</h3>
            <p style="color: #047857; margin: 0 0 1rem 0; font-size: 0.85rem;">Make sure to copy your API Secret now. You will not be able to see it again!</p>
            <div style="background: #ffffff; padding: 1rem; border-radius: 8px; font-family: monospace; font-size: 0.9rem; border: 1px solid #a7f3d0;">
                <div><strong>API Key (X-API-Key):</strong> <code><?= e($newCred['api_key']) ?></code></div>
                <div style="margin-top: 0.5rem; color: #b91c1c;"><strong>API Secret (X-API-Secret):</strong> <code><?= e($newCred['api_secret']) ?></code></div>
                <div style="margin-top: 0.5rem; color: #4b5563;"><strong>Environment:</strong> <code><?= e($newCred['environment']) ?></code></div>
            </div>
        </div>
    <?php endif; ?>

    <div style="grid-template-columns: 1fr 1fr; display: grid; gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Generate New Key Card -->
        <div style="background: #ffffff; border-radius: 12px; padding: 1.5rem; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <h3 style="margin: 0 0 1rem 0; font-size: 1.1rem; font-weight: 700; color: #1e293b;">Generate New API Key</h3>
            <form action="<?= \App\Core\View::url('/customer/api-keys/create') ?>" method="POST">
                <?= csrf_field() ?>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Application Name</label>
                    <input type="text" name="name" required placeholder="e.g. Shopify Store Integration" style="width: 100%; padding: 0.65rem; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.9rem;">
                </div>
                <div style="margin-bottom: 1.25rem;">
                    <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 0.4rem;">Environment</label>
                    <select name="environment" style="width: 100%; padding: 0.65rem; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 0.9rem;">
                        <option value="live">Live (Production Network)</option>
                        <option value="test">Test (Sandbox Environment)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-gold" style="width: 100%; padding: 0.75rem; font-weight: 700; border-radius: 8px;">Generate Credentials</button>
            </form>
        </div>

        <!-- Quick API Integration Info -->
        <div style="background: #0f172a; color: #f8fafc; border-radius: 12px; padding: 1.5rem;">
            <h3 style="margin: 0 0 0.75rem 0; font-size: 1.1rem; font-weight: 700; color: #f1c45e;">API Request Header Standard</h3>
            <p style="font-size: 0.85rem; color: #94a3b8; margin: 0 0 1rem 0;">Pass your API Key and Secret headers in every REST request:</p>
            <pre style="background: #1e293b; padding: 1rem; border-radius: 8px; font-size: 0.8rem; color: #38bdf8; overflow-x: auto; margin: 0;">X-API-Key: rc_live_7f8a9b0c1d2e...
X-API-Secret: sec_a1b2c3d4e5f6...
Content-Type: application/json</pre>
            <div style="margin-top: 1rem; font-size: 0.8rem; color: #cbd5e1;">
                <strong>Base URL:</strong> <code>https://rapid-courier.com/api/v1/</code>
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
                    <th style="padding: 0.75rem 1rem;">Name</th>
                    <th style="padding: 0.75rem 1rem;">API Key</th>
                    <th style="padding: 0.75rem 1rem;">Environment</th>
                    <th style="padding: 0.75rem 1rem;">Rate Limit</th>
                    <th style="padding: 0.75rem 1rem;">Status</th>
                    <th style="padding: 0.75rem 1rem;">Created</th>
                    <th style="padding: 0.75rem 1rem; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($keys)): ?>
                    <tr><td colspan="7" style="padding: 2rem; text-align: center; color: #94a3b8;">No API keys generated yet. Use the form above to generate your first key.</td></tr>
                <?php else: ?>
                    <?php foreach ($keys as $k): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.75rem 1rem; font-weight: 700;"><?= e($k['name']) ?></td>
                            <td style="padding: 0.75rem 1rem; font-family: monospace; font-size: 0.8rem;"><?= e($k['api_key']) ?></td>
                            <td style="padding: 0.75rem 1rem;"><span style="background: #e0f2fe; color: #0369a1; padding: 2px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase; font-size: 0.75rem;"><?= e($k['environment']) ?></span></td>
                            <td style="padding: 0.75rem 1rem;"><?= (int)$k['rate_limit_rpm'] ?> req/min</td>
                            <td style="padding: 0.75rem 1rem;">
                                <?php if ($k['status'] === 'active'): ?>
                                    <span style="color: #10b981; font-weight: 700;">● Active</span>
                                <?php else: ?>
                                    <span style="color: #ef4444; font-weight: 700;">● Revoked</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 0.75rem 1rem; color: #64748b;"><?= date('M d, Y', strtotime($k['created_at'])) ?></td>
                            <td style="padding: 0.75rem 1rem; text-align: right;">
                                <?php if ($k['status'] === 'active'): ?>
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

    <!-- API Audit Logs -->
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #e2e8f0; background: #f8fafc;">
            <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: #1e293b;">Recent API Request Logs</h3>
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
