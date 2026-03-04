-- ============================================================
-- GoGroup Datenbank - Initialisierungsscript
-- MSSQL Server kompatibel
-- ============================================================

-- Datenbank erstellen
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'GoGroup')
BEGIN
    CREATE DATABASE [GoGroup];
END
GO

USE [GoGroup];
GO

-- ============================================================
-- TABELLEN ERSTELLEN
-- ============================================================

-- Rolle
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'ROLE' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[ROLE] (
        [RoleId]  INT          IDENTITY(1,1) NOT NULL,
        [Name]    VARCHAR(100) NOT NULL,
        CONSTRAINT [PK_ROLE] PRIMARY KEY CLUSTERED ([RoleId])
    );
END
GO

-- Rechte / Berechtigungen
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'PERMISSIONS' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[PERMISSIONS] (
        [PermissionId]   INT           IDENTITY(1,1) NOT NULL,
        [RoleId]         INT           NOT NULL,
        [PermissionName] NVARCHAR(100) NOT NULL,
        CONSTRAINT [PK_PERMISSIONS]      PRIMARY KEY CLUSTERED ([PermissionId]),
        CONSTRAINT [FK_PERMISSIONS_ROLE] FOREIGN KEY ([RoleId]) REFERENCES [dbo].[ROLE]([RoleId]) ON DELETE CASCADE
    );
END
GO

-- Benutzer
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'USER' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[USER] (
        [UserId]    INT          IDENTITY(1,1) NOT NULL,
        [FirstName] VARCHAR(100) NOT NULL,
        [LastName]  VARCHAR(100) NOT NULL,
        [RoleId]    INT          NOT NULL,
        CONSTRAINT [PK_USER]      PRIMARY KEY CLUSTERED ([UserId]),
        CONSTRAINT [FK_USER_ROLE] FOREIGN KEY ([RoleId]) REFERENCES [dbo].[ROLE]([RoleId])
    );
END
GO

-- Login
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'LOGIN' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[LOGIN] (
        [LoginId]      INT          IDENTITY(1,1) NOT NULL,
        [Username]     VARCHAR(100) NOT NULL,
        [UserPassword] VARCHAR(255) NOT NULL,   -- mind. 255 für bcrypt-Hashes
        [UserId]       INT          NOT NULL,
        CONSTRAINT [PK_LOGIN]      PRIMARY KEY CLUSTERED ([LoginId]),
        CONSTRAINT [UQ_LOGIN_USERNAME] UNIQUE ([Username]),
        CONSTRAINT [FK_LOGIN_USER] FOREIGN KEY ([UserId]) REFERENCES [dbo].[USER]([UserId]) ON DELETE CASCADE
    );
END
GO

-- Kalender
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'CALENDAR' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[CALENDAR] (
        [CalendarId] INT IDENTITY(1,1) NOT NULL,
        [Name]       VARCHAR(200) NULL,        -- optionaler Kalendernahme
        CONSTRAINT [PK_CALENDAR] PRIMARY KEY CLUSTERED ([CalendarId])
    );
END
GO

-- Kalender-Eintrag
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'CALENDAR_ENTRY' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[CALENDAR_ENTRY] (
        [CalendarEntryId] INT           IDENTITY(1,1) NOT NULL,
        [CalendarId]      INT           NOT NULL,
        [Title]           VARCHAR(1000) NOT NULL,
        [Description]     VARCHAR(MAX)  NULL,
        [StartDate]       DATETIME2     NOT NULL,
        [EndDate]         DATETIME2     NOT NULL,
        CONSTRAINT [PK_CALENDAR_ENTRY]         PRIMARY KEY CLUSTERED ([CalendarEntryId]),
        CONSTRAINT [FK_CALENDAR_ENTRY_CALENDAR] FOREIGN KEY ([CalendarId]) REFERENCES [dbo].[CALENDAR]([CalendarId]) ON DELETE CASCADE,
        CONSTRAINT [CK_CALENDAR_ENTRY_DATES]   CHECK ([EndDate] >= [StartDate])
    );
END
GO

-- Gruppe
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'GROUP' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[GROUP] (
        [GroupId]    INT          IDENTITY(1,1) NOT NULL,
        [Name]       VARCHAR(100) NOT NULL,
        [CalendarId] INT          NULL,
        CONSTRAINT [PK_GROUP]          PRIMARY KEY CLUSTERED ([GroupId]),
        CONSTRAINT [FK_GROUP_CALENDAR] FOREIGN KEY ([CalendarId]) REFERENCES [dbo].[CALENDAR]([CalendarId])
    );
END
GO

-- Tabellen-Nummern (Referenz-Lookup)
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'TABLENUMBER' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[TABLENUMBER] (
        [TableNumber] INT          IDENTITY(1,1) NOT NULL,
        [Name]        VARCHAR(100) NOT NULL,
        CONSTRAINT [PK_TABLENUMBER]    PRIMARY KEY CLUSTERED ([TableNumber]),
        CONSTRAINT [UQ_TABLENUMBER_NAME] UNIQUE ([Name])
    );
END
GO

-- Referenz (generische Zeiger auf beliebige Tabellen)
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'REFERENCE' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[REFERENCE] (
        [ReferenceId]         INT IDENTITY(1,1) NOT NULL,
        [RegardingTableNumber] INT NOT NULL,
        [RegardingId]         INT NOT NULL,
        CONSTRAINT [PK_REFERENCE]              PRIMARY KEY CLUSTERED ([ReferenceId]),
        CONSTRAINT [FK_REFERENCE_TABLENUMBER]  FOREIGN KEY ([RegardingTableNumber]) REFERENCES [dbo].[TABLENUMBER]([TableNumber]),
        CONSTRAINT [UQ_REFERENCE]              UNIQUE ([RegardingTableNumber], [RegardingId])
    );
END
GO

-- Ordner
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'FOLDER' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[FOLDER] (
        [FolderId]       INT          IDENTITY(1,1) NOT NULL,
        [Name]           VARCHAR(100) NOT NULL,
        [ParentFolderId] INT          NULL,   -- NULL = Root-Ordner
        [RegardingId]    INT          NULL,   -- Verknüpfung zu REFERENCE (optional)
        [OwnerId]        INT          NOT NULL,
        [IsRoot]         BIT          NOT NULL DEFAULT(0),
        CONSTRAINT [PK_FOLDER]               PRIMARY KEY CLUSTERED ([FolderId]),
        CONSTRAINT [FK_FOLDER_PARENT]        FOREIGN KEY ([ParentFolderId]) REFERENCES [dbo].[FOLDER]([FolderId]),
        CONSTRAINT [FK_FOLDER_REFERENCE]     FOREIGN KEY ([RegardingId])    REFERENCES [dbo].[REFERENCE]([ReferenceId]),
        CONSTRAINT [FK_FOLDER_USER]          FOREIGN KEY ([OwnerId])        REFERENCES [dbo].[USER]([UserId])
    );
END
GO

-- Projekt
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'PROJECT' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[PROJECT] (
        [ProjectId]       INT           IDENTITY(1,1) NOT NULL,
        [Title]           VARCHAR(1000) NOT NULL,
        [Description]     VARCHAR(MAX)  NULL,
        [DueDate]         DATETIME2     NULL,
        [GroupId]         INT           NOT NULL,
        [DropOfFolderId]  INT           NULL,   -- wird per Trigger befüllt
        [OwnerId]         INT           NOT NULL,
        CONSTRAINT [PK_PROJECT]              PRIMARY KEY CLUSTERED ([ProjectId]),
        CONSTRAINT [FK_PROJECT_GROUP]        FOREIGN KEY ([GroupId])        REFERENCES [dbo].[GROUP]([GroupId]),
        CONSTRAINT [FK_PROJECT_FOLDER]       FOREIGN KEY ([DropOfFolderId]) REFERENCES [dbo].[FOLDER]([FolderId]),
        CONSTRAINT [FK_PROJECT_USER]         FOREIGN KEY ([OwnerId])        REFERENCES [dbo].[USER]([UserId])
    );
END
GO

-- Aufgabe
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'TASK' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[TASK] (
        [TaskId]      INT           IDENTITY(1,1) NOT NULL,
        [Title]       VARCHAR(1000) NOT NULL,
        [Description] VARCHAR(MAX)  NULL,
        [Status]      TINYINT       NOT NULL DEFAULT(0),   -- 0=offen, 1=in Bearbeitung, 2=erledigt
        [DueDate]     DATETIME2     NULL,
        [OwnerId]     INT           NOT NULL,
        [ProjectId]   INT           NOT NULL,
        CONSTRAINT [PK_TASK]         PRIMARY KEY CLUSTERED ([TaskId]),
        CONSTRAINT [FK_TASK_USER]    FOREIGN KEY ([OwnerId])   REFERENCES [dbo].[USER]([UserId]),
        CONSTRAINT [FK_TASK_PROJECT] FOREIGN KEY ([ProjectId]) REFERENCES [dbo].[PROJECT]([ProjectId]) ON DELETE CASCADE,
        CONSTRAINT [CK_TASK_STATUS]  CHECK ([Status] IN (0, 1, 2))
    );
END
GO

-- Chat
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'CHAT' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[CHAT] (
        [ChatId]  INT           IDENTITY(1,1) NOT NULL,
        [Name]    VARCHAR(1000) NOT NULL,
        [GroupId] INT           NULL,   -- NULL = privater / direkter Chat
        CONSTRAINT [PK_CHAT]       PRIMARY KEY CLUSTERED ([ChatId]),
        CONSTRAINT [FK_CHAT_GROUP] FOREIGN KEY ([GroupId]) REFERENCES [dbo].[GROUP]([GroupId]) ON DELETE SET NULL
    );
END
GO

-- Mitglied (User ↔ Gruppe  oder  User ↔ Chat)
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'MEMBER' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[MEMBER] (
        [MemberId] INT IDENTITY(1,1) NOT NULL,
        [UserId]   INT NOT NULL,
        [ChatId]   INT NULL,
        [GroupId]  INT NULL,
        CONSTRAINT [PK_MEMBER]       PRIMARY KEY CLUSTERED ([MemberId]),
        CONSTRAINT [FK_MEMBER_USER]  FOREIGN KEY ([UserId])  REFERENCES [dbo].[USER]([UserId])  ON DELETE CASCADE,
        CONSTRAINT [FK_MEMBER_CHAT]  FOREIGN KEY ([ChatId])  REFERENCES [dbo].[CHAT]([ChatId]),
        CONSTRAINT [FK_MEMBER_GROUP] FOREIGN KEY ([GroupId]) REFERENCES [dbo].[GROUP]([GroupId]),
        -- Jeder User darf nur einmal pro Gruppe / Chat stehen
        CONSTRAINT [UQ_MEMBER_USER_GROUP] UNIQUE ([UserId], [GroupId]),
        CONSTRAINT [CK_MEMBER_TARGET] CHECK ([ChatId] IS NOT NULL OR [GroupId] IS NOT NULL)
    );
END
GO

-- Datei (mit temporaler Versionierung)
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'FILE' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[FILE] (
        [FileId]       INT           IDENTITY(1,1) NOT NULL,
        [Name]         VARCHAR(1000) NOT NULL,
        [FolderId]     INT           NOT NULL,
        [OwnerId]      INT           NOT NULL,
        [Content]      VARBINARY(MAX) NOT NULL,
        [SysStartTime] DATETIME2 GENERATED ALWAYS AS ROW START NOT NULL,
        [SysEndTime]   DATETIME2 GENERATED ALWAYS AS ROW END   NOT NULL,
        PERIOD FOR SYSTEM_TIME ([SysStartTime], [SysEndTime]),
        CONSTRAINT [PK_FILE]        PRIMARY KEY CLUSTERED ([FileId]),
        CONSTRAINT [FK_FILE_FOLDER] FOREIGN KEY ([FolderId]) REFERENCES [dbo].[FOLDER]([FolderId]),
        CONSTRAINT [FK_FILE_USER]   FOREIGN KEY ([OwnerId])  REFERENCES [dbo].[USER]([UserId])
    )
    WITH (SYSTEM_VERSIONING = ON (HISTORY_TABLE = [dbo].[FILE_History]));
END
GO

-- Chat-Nachricht
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'CHAT_MESSAGE' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[CHAT_MESSAGE] (
        [ChatMessageId] INT          IDENTITY(1,1) NOT NULL,
        [ChatId]        INT          NOT NULL,
        [FromUserId]    INT          NOT NULL,
        [Message]       VARCHAR(MAX) NOT NULL,
        [TimeStamp]     DATETIME2    NOT NULL DEFAULT SYSUTCDATETIME(),
        CONSTRAINT [PK_CHAT_MESSAGE]      PRIMARY KEY CLUSTERED ([ChatMessageId]),
        CONSTRAINT [FK_CHAT_MESSAGE_CHAT] FOREIGN KEY ([ChatId])     REFERENCES [dbo].[CHAT]([ChatId]) ON DELETE CASCADE,
        CONSTRAINT [FK_CHAT_MESSAGE_USER] FOREIGN KEY ([FromUserId]) REFERENCES [dbo].[USER]([UserId])
    );
END
GO

-- Benachrichtigung
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'NOTIFICATION' AND type = 'U')
BEGIN
    CREATE TABLE [dbo].[NOTIFICATION] (
        [NotificationId] INT          IDENTITY(1,1) NOT NULL,
        [ToUserId]       INT          NOT NULL,
        [Message]        VARCHAR(MAX) NOT NULL,
        [IsRead]         BIT          NOT NULL DEFAULT(0),
        [RegardingId]    INT          NOT NULL,
        [TimeStamp]      DATETIME2    NOT NULL DEFAULT SYSUTCDATETIME(),
        CONSTRAINT [PK_NOTIFICATION]             PRIMARY KEY CLUSTERED ([NotificationId]),
        CONSTRAINT [FK_NOTIFICATION_USER]        FOREIGN KEY ([ToUserId])    REFERENCES [dbo].[USER]([UserId]) ON DELETE CASCADE,
        CONSTRAINT [FK_NOTIFICATION_REFERENCE]   FOREIGN KEY ([RegardingId]) REFERENCES [dbo].[REFERENCE]([ReferenceId])
    );
END
GO

-- ============================================================
-- STORED PROCEDURES
-- ============================================================

-- Referenz erstellen oder abrufen
DROP PROCEDURE IF EXISTS [dbo].[GetOrCreateReference];
GO

CREATE PROCEDURE [dbo].[GetOrCreateReference]
    @RegardingTableNumber INT,
    @RegardingId          INT,
    @ReferenceId          INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;

    SELECT @ReferenceId = [ReferenceId]
    FROM   [dbo].[REFERENCE]
    WHERE  [RegardingTableNumber] = @RegardingTableNumber
      AND  [RegardingId]          = @RegardingId;

    IF @ReferenceId IS NULL
    BEGIN
        INSERT INTO [dbo].[REFERENCE] ([RegardingTableNumber], [RegardingId])
        VALUES (@RegardingTableNumber, @RegardingId);

        SET @ReferenceId = SCOPE_IDENTITY();
    END
END;
GO

-- ============================================================
-- TRIGGER
-- ============================================================

-- Nach INSERT auf PROJECT: Abgabe-Ordner + Root-Ordner anlegen
DROP TRIGGER IF EXISTS [dbo].[trg_Project_AfterInsert];
GO

CREATE TRIGGER [dbo].[trg_Project_AfterInsert]
ON [dbo].[PROJECT]
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ProjectId          INT;
    DECLARE @OwnerId            INT;
    DECLARE @Title              VARCHAR(1000);
    DECLARE @DropOfFolderId     INT;
    DECLARE @ProjectReferenceId INT;

    SELECT @ProjectId = [ProjectId],
           @OwnerId   = [OwnerId],
           @Title     = [Title]
    FROM   inserted;

    -- Abgabe-Ordner anlegen
    INSERT INTO [dbo].[FOLDER] ([Name], [OwnerId], [IsRoot])
    VALUES (
        'Projektabgabe - [' + CAST(@ProjectId AS VARCHAR) + '] - ' + @Title,
        @OwnerId,
        1
    );
    SET @DropOfFolderId = SCOPE_IDENTITY();

    -- Projekt mit dem neuen Ordner verknüpfen
    UPDATE [dbo].[PROJECT]
    SET    [DropOfFolderId] = @DropOfFolderId
    WHERE  [ProjectId]      = @ProjectId;

    -- Generische Referenz auf das Projekt anlegen
    EXEC [dbo].[GetOrCreateReference]
        @RegardingTableNumber = 1,   -- PROJECT
        @RegardingId          = @ProjectId,
        @ReferenceId          = @ProjectReferenceId OUTPUT;

    -- Root-Ordner für Projektdateien anlegen
    INSERT INTO [dbo].[FOLDER] ([Name], [OwnerId], [IsRoot], [RegardingId])
    VALUES ('Root', @OwnerId, 1, @ProjectReferenceId);
END;
GO

-- ============================================================
-- STAMMDATEN / TESTDATEN
-- ============================================================

-- Rollen
IF (SELECT COUNT(*) FROM [dbo].[ROLE]) = 0
BEGIN
    INSERT INTO [dbo].[ROLE] ([Name]) VALUES
        ('Admin'),   -- RoleId 1
        ('Schüler'), -- RoleId 2
        ('Lehrer');  -- RoleId 3
END
GO

-- Berechtigungen
IF (SELECT COUNT(*) FROM [dbo].[PERMISSIONS]) = 0
BEGIN
    INSERT INTO [dbo].[PERMISSIONS] ([RoleId], [PermissionName]) VALUES
        (1, 'admin'),
        (2, 'user'),
        (3, 'teacher');
END
GO

-- Benutzer
IF (SELECT COUNT(*) FROM [dbo].[USER]) = 0
BEGIN
    INSERT INTO [dbo].[USER] ([FirstName], [LastName], [RoleId]) VALUES
        ('Luca',  'Brüning',  1),
        ('Simon', 'Krainert', 1),
        ('Kyra',  'Mitwollen', 1),
        ('Larissa', 'Windhorst', 1);
END
GO

-- Logins (php-Hashes)
IF (SELECT COUNT(*) FROM [dbo].[LOGIN]) = 0
BEGIN
    INSERT INTO [dbo].[LOGIN] ([Username], [UserPassword], [UserId]) VALUES
        ('luca.bruening',  '$2y$12$p.JE.9o2a9Ea8HWQE7QUiOPDftMMEHo4eEVUvL5DlpUExtzCAOn/O', 1),
        ('simon.krainert', '$2y$12$gn6.Qu2VqsrNP5h.Nxe23OKWtP6zK4Z0C5cbSw3ft1TkmiremZ9LC', 2),
        ('kyra.mitwollen', '$2y$10$E3ms.OkOxXwukrItKcPafu9gREdwrZ.fryj2yVsk6T6a1HcQQii4a', 3),
        ('larissa.windhorst', '$2y$10$3IqOfPVbV5CcIaskD2bLnuoF.9mhCYar4.yMNBoC81rvHUIMvK0Ni', 4);
END
GO

-- Tabellen-Nummern (Lookup für generische Referenzen)
IF (SELECT COUNT(*) FROM [dbo].[TABLENUMBER]) = 0
BEGIN
    INSERT INTO [dbo].[TABLENUMBER] ([Name]) VALUES
        ('PROJECT'),        -- 1
        ('TASK'),           -- 2
        ('CHAT'),           -- 3
        ('FOLDER'),         -- 4
        ('FILE'),           -- 5
        ('GROUP'),          -- 6
        ('CALENDAR'),       -- 7
        ('CALENDAR_ENTRY'); -- 8
END
GO

-- Gruppen
IF (SELECT COUNT(*) FROM [dbo].[GROUP]) = 0
BEGIN
    INSERT INTO [dbo].[GROUP] ([Name]) VALUES
        ('Komm in die Gruppe xD'),
        ('GoGroup Test Gruppe'),
        ('Die Normalen'),
        ('Die Coolen');
END
GO

-- Gruppen-Mitglieder
IF (SELECT COUNT(*) FROM [dbo].[MEMBER]) = 0
BEGIN
    INSERT INTO [dbo].[MEMBER] ([UserId], [GroupId]) VALUES
        (1, 1),
        (2, 1),
        (1, 2),
        (2, 2);
END
GO

-- Projekte
IF (SELECT COUNT(*) FROM [dbo].[PROJECT]) = 0
BEGIN
    INSERT INTO [dbo].[PROJECT] ([Title], [DueDate], [Description], [GroupId], [DropOfFolderId], [OwnerId]) VALUES
        ('Mathe Projekt',   '2024-12-31', 'Beschreibung für Projekt 1',  1, NULL, 1),
        ('GoGroup Projekt', NULL,         'Beschreibung für Projekt 2',  1, NULL, 2);
END
GO

-- Aufgaben
IF (SELECT COUNT(*) FROM [dbo].[TASK]) = 0
BEGIN
    INSERT INTO [dbo].[TASK] ([Title], [Description], [Status], [DueDate], [OwnerId], [ProjectId]) VALUES
        ('Mathe Aufgabe 1',    'Beschreibung für Aufgabe 1',           0, '2024-11-30', 1, 1),
        ('Mathe Aufgabe 2',    'Beschreibung für Aufgabe 2',           0, '2024-12-15', 2, 1),
        ('GoGroup Aufgabe 1',  'Beschreibung für GoGroup Aufgabe 1',   0, NULL,         1, 2);
END
GO