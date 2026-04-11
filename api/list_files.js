const SftpClient = require('ssh2-sftp-client');

export default async function handler(req, res) {
  if (req.method !== 'POST') return res.status(405).end();

  res.setHeader('Content-Type', 'application/json; charset=utf-8');

  const { comp, file, action, server } = req.body;

  if (!comp) return res.status(400).json({ error: "Номер комп'ютера не вказано." });

  const compClean = comp.replace(/[^0-9]/g, '');
  const fileClean = file ? file.replace(/[^a-zA-Z0-9_\-\.]/g, '') : '';

  const builtinUsers = {
    server1: 'admin.3c4202c1',
    server2: 'admin.cfc9be31',
  };

  let sftpConfig;
  if (server && server.host) {
    sftpConfig = {
      host: server.host,
      port: parseInt(server.port || '2022'),
      username: server.user,
      password: server.pass || (builtinUsers[server.id] ? process.env.SFTP_PASS : ''),
    };
  } else {
    sftpConfig = {
      host: process.env.SFTP_HOST || '46.225.227.42',
      port: parseInt(process.env.SFTP_PORT || '2022'),
      username: process.env.SFTP_USER || 'admin.3c4202c1',
      password: process.env.SFTP_PASS,
    };
  }

  const basePath = (server && server.basePath) || 'world/computercraft/computer/';

  const sftp = new SftpClient();

  try {
    await sftp.connect(sftpConfig);

    const dir = `${basePath}${compClean}`;

    if (action === 'delete' && fileClean) {
      await sftp.delete(`${dir}/${fileClean}`);
      await sftp.end();
      return res.json({ ok: true });

    } else if (fileClean) {
      const content = await sftp.get(`${dir}/${fileClean}`);
      await sftp.end();
      return res.json({ name: fileClean, content: content.toString('utf8') });

    } else {
      const list = await sftp.list(dir).catch(() => []);
      await sftp.end();
      const luaFiles = list.filter(f => f.name.endsWith('.lua')).map(f => f.name);
      return res.json({ files: luaFiles });
    }

  } catch (err) {
    await sftp.end().catch(() => {});
    return res.status(400).json({ error: err.message });
  }
}
