const SftpClient = require('ssh2-sftp-client');

export default async function handler(req, res) {
  if (req.method !== 'POST') return res.status(405).end();

  const { code, comp, fileName } = req.body;

  if (!code || !comp || !fileName) {
    return res.status(400).send('⛔ Обов\'язкові поля відсутні або порожні.');
  }

  const compClean = comp.replace(/[^0-9]/g, '');
  const fileClean = fileName.replace(/[^a-zA-Z0-9_\-\.]/g, '');

  const sftp = new SftpClient();

  try {
    await sftp.connect({
      host: process.env.SFTP_HOST || '46.225.227.42',
      port: parseInt(process.env.SFTP_PORT || '2022'),
      username: process.env.SFTP_USER || 'admin.3c4202c1',
      password: process.env.SFTP_PASS,
    });

    const dir = `world/computercraft/computer/${compClean}`;
    const remotePath = `${dir}/${fileClean}`;

    await sftp.mkdir(dir, true).catch(() => {});
    await sftp.put(Buffer.from(code, 'utf8'), remotePath);
    await sftp.end();

    res.status(200).send(`✅ Файл збережено у: ${remotePath}`);
  } catch (err) {
    await sftp.end().catch(() => {});
    res.status(500).send(`❌ ${err.message}`);
  }
}
