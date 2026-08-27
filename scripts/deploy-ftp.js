const ftp = require("basic-ftp");
const path = require("path");
const fs = require("fs");
const readline = require("readline");

// Lista dei file del progetto da sincronizzare su Aruba
const filesToDeploy = [
    'routes/tenant.php',
    'app/Http/Controllers/Auth/ForgotPasswordController.php',
    'resources/views/frontend/infixlmstheme/auth/login.blade.php',
    'Modules/RolePermission/Entities/Role.php',
    'Modules/RolePermission/Repositories/RoleRepository.php',
    'Modules/RolePermission/Resources/views/index.blade.php',
    'resources/lang/it/common.php',
    'resources/lang/it/role.php',
    'database/migrations/2026_08_27_000000_add_funzionario_esterno_role.php'
];

function prompt(question) {
    const rl = readline.createInterface({ input: process.stdin, output: process.stdout });
    return new Promise(resolve => rl.question(question, ans => { rl.close(); resolve(ans.trim()); }));
}

async function deploy() {
    let host = process.env.FTP_HOST || "ftp.corsi.aletheiasrl.it";
    let user = process.env.FTP_USER;
    let password = process.env.FTP_PASSWORD;

    if (!user) {
        user = await prompt("Inserisci Nome Utente FTP Aruba: ");
    }
    if (!password) {
        password = await prompt("Inserisci Password FTP Aruba: ");
    }

    const client = new ftp.Client(30000);
    client.ftp.verbose = false;

    try {
        console.log(`\n🚀 Connessione in corso a ${host}...`);
        await client.access({
            host: host,
            user: user,
            password: password,
            port: 21,
            secure: false
        });

        console.log("✅ Connessione FTP stabilita con successo!");
        console.log("📂 Caricamento dei file aggiornati su corsi.aletheiasrl.it...\n");

        const initialDir = await client.pwd();
        console.log(`📍 Cartella remota iniziale: ${initialDir}`);

        const rootList = await client.list();
        console.log("📂 Cartelle trovate sul server:", rootList.map(i => i.name).join(", ") || "(vuota)");
        console.log("");

        async function cdOrCreateRelative(dirPath) {
            const parts = dirPath.split("/").filter(p => p.length > 0 && p !== ".");
            for (const part of parts) {
                try {
                    await client.cd(part);
                } catch (e) {
                    try {
                        await client.send("MKD " + part);
                    } catch (err) {}
                    await client.cd(part);
                }
            }
        }

        for (const relPath of filesToDeploy) {
            const localPath = path.join(__dirname, "..", relPath);
            const normalizedRelPath = relPath.replace(/\\/g, "/");

            if (fs.existsSync(localPath)) {
                await client.cd(initialDir);
                const remoteDir = path.dirname(normalizedRelPath);
                if (remoteDir && remoteDir !== ".") {
                    await cdOrCreateRelative(remoteDir);
                }
                const fileName = path.basename(normalizedRelPath);
                await client.uploadFrom(localPath, fileName);
                console.log(`✓ Caricato con successo: ${relPath}`);
            } else {
                console.warn(`⚠️ File non trovato in locale: ${relPath}`);
            }
        }

        console.log("\n🎉 DEPLOY COMPLETATO CON SUCCESSO SU ARUBA! 🎉");
    } catch (err) {
        console.error("\n❌ Errore durante il caricamento FTP:", err.message);
    } finally {
        client.close();
    }
}

deploy();
