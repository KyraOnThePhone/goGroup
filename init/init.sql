-- Datenbank erstellen
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'GoGroup')
BEGIN
    CREATE DATABASE [GoGroup]
END
GO

USE [GoGroup]
GO

-- Tabellen Erstellen
-- Rolle
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'ROLE' AND type = 'U')
BEGIN
    CREATE TABLE [ROLE](  
        [RoleId] int IDENTITY(1,1) primary key,
        [Name] varchar(100) NOT NULL
    )
END GO

-- Benutzer
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'USER' AND type = 'U')
BEGIN
    CREATE TABLE [USER](  
        [UserId] int IDENTITY(1,1) primary key,
        [FirstName] varchar(100) NOT NULL,
        [LastName] varchar(100) NOT NULL,
        [RoleId] int FOREIGN KEY REFERENCES [ROLE](RoleId) NOT NULL
    )
END GO

-- Login
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'LOGIN' AND type = 'U')
BEGIN
    CREATE TABLE [LOGIN](  
        [LoginId] int IDENTITY(1,1) primary key,
        [Username] varchar(100) NOT NULL,
        [UserPassword] varchar(100) NOT NULL,
        [UserId] int FOREIGN KEY REFERENCES [USER](UserId) NOT NULL
    )
END GO

-- Kalender
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'CALENDAR' AND type = 'U')
BEGIN
    CREATE TABLE [CALENDAR](  
        [CalendarId] int IDENTITY(1,1) primary key
    )
END GO

-- Kalender Eintrag
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'CALENDAR_ENTRY' AND type = 'U')
BEGIN
    CREATE TABLE [CALENDAR_ENTRY](  
        [CalendarEntryId] int IDENTITY(1,1) primary key,
        [CalendarId] int FOREIGN KEY REFERENCES [CALENDAR](CalendarId) NOT NULL,
        [Title] varchar(1000) NOT NULL,
        [Description] varchar(MAX),
        [StartDate] DATETIME NOT NULL,
        [EndDate] DATETIME NOT NULL,
    )
END GO

--Gruppe
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'GROUP' AND type = 'U')
BEGIN
    CREATE TABLE [GROUP](
        [GroupId] int IDENTITY(1,1) primary key,
        [Name] varchar(100) NOT NULL,
        [CalendarId] int FOREIGN KEY REFERENCES [CALENDAR](CalendarId) NOT NULL
    )
END GO

-- Tabellen-Nr
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'TABLENUMBER' AND type = 'U')
BEGIN
    CREATE TABLE [TABLENUMBER](
        [TableNumber] int IDENTITY(1,1) primary key,
        [Name] varchar(100) NOT NULL
    )
END GO

-- Bezug
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'REFERENCE' AND type = 'U')
BEGIN
    CREATE TABLE [REFERENCE](
        [ReferenceId] int IDENTITY(1,1) primary key,
        [RegardingTableNumber] int FOREIGN KEY REFERENCES [TABLENUMBER](TableNumber) NOT NULL,
        [RegardingId] int NOT NULL
    )
END GO

-- Ordner
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'FOLDER' AND type = 'U')
BEGIN
    CREATE TABLE [FOLDER](
        [FolderId] int IDENTITY(1,1) primary key,
        [Name] varchar(100) NOT NULL,
        [ParentFolderId] int FOREIGN KEY REFERENCES [FOLDER](FolderId),
        [RegardingId] int FOREIGN KEY REFERENCES [REFERENCE](ReferenceId),
        [OwnerId] int FOREIGN KEY REFERENCES [USER](UserId) NOT NULL,
        [IsRoot] BIT NOT NULL
    )
END GO

--Projekt
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'PROJECT' AND type = 'U')
BEGIN
    CREATE TABLE [PROJECT](
        [ProjectId] int IDENTITY(1,1) primary key,
        [Title] varchar(1000) NOT NULL,
        [DueDate] DATETIME,
        [Description] varchar(MAX),
        [GroupId] int FOREIGN KEY REFERENCES [GROUP](GroupId) NOT NULL,
        [DropOfFolderId] int FOREIGN KEY REFERENCES [FOLDER](FolderId) NOT NULL,
        [OwnerId] int FOREIGN KEY REFERENCES [USER](UserId) NOT NULL
    )
END GO


--Status
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'STATUS' AND type = 'U')
BEGIN
    CREATE TABLE [STATUS](
        [StatusId] int IDENTITY(1,1) primary key,
        [Name] varchar(100) NOT NULL,
        [RegardingTableNumber] int FOREIGN KEY REFERENCES [TABLENUMBER](TableNumber) NOT NULL
    )
END GO

--Aufgabe
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'TASK' AND type = 'U')
BEGIN
    CREATE TABLE [TASK](
        [TaskId] int IDENTITY(1,1) primary key,
        [Title] varchar(1000) NOT NULL,
        [Description] varchar(MAX),
        [StateId] int FOREIGN KEY REFERENCES [STATUS](StatusId) NOT NULL,
        [DueDate] DATETIME,
        [OwnerId] int FOREIGN KEY REFERENCES [USER](UserId) NOT NULL,
        [ProjectId] int FOREIGN KEY REFERENCES [PROJECT](ProjectId) NOT NULL
    )
END GO

-- Chat
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'CHAT' AND type = 'U')
BEGIN
    CREATE TABLE [CHAT](
        [ChatId] int IDENTITY(1,1) primary key,
        [Name] VARCHAR(1000) NOT NULL,    
        [GroupId] int FOREIGN KEY REFERENCES [GROUP](GroupId),
    )
END GO

-- Mitglied
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'MEMBER' AND type = 'U')
BEGIN
    CREATE TABLE [MEMBER](
        [MemberId] int IDENTITY(1,1) primary key,
        [UserId] int FOREIGN KEY REFERENCES [USER](UserId) NOT NULL,
        [RegardingId] int FOREIGN KEY REFERENCES [REFERENCE](ReferenceId) NOT NULL
    )
END GO

-- OAuth
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'OAUTH' AND type = 'U')
BEGIN
    CREATE TABLE [OAUTH](
        [OAuthId] int IDENTITY(1,1) primary key,
        [UserId] int FOREIGN KEY REFERENCES [USER](UserId) NOT NULL,
        [OAuthKey] varchar(100) NOT NULL,
        [ExpiresAt] DATETIME NOT NULL
    )
END GO

-- Datei
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'FILE' AND type = 'U')
BEGIN
    CREATE TABLE [FILE](
        [FileId] int IDENTITY(1,1) NOT NULL,
        [Name] varchar(1000) NOT NULL,
        [FolderId] int NOT NULL,
        [OwnerId] int NOT NULL,
        [Content] VARBINARY(MAX) NOT NULL,
        
        [SysStartTime] DATETIME2 GENERATED ALWAYS AS ROW START NOT NULL,
        [SysEndTime] DATETIME2 GENERATED ALWAYS AS ROW END NOT NULL,
        
        PERIOD FOR SYSTEM_TIME (SysStartTime, SysEndTime),
        
        CONSTRAINT [PK_FILE] PRIMARY KEY CLUSTERED ([FileId]),
        CONSTRAINT [FK_FILE_FOLDER] FOREIGN KEY ([FolderId]) REFERENCES [FOLDER]([FolderId]),
        CONSTRAINT [FK_FILE_USER] FOREIGN KEY ([OwnerId]) REFERENCES [USER]([UserId])
    )
    WITH (SYSTEM_VERSIONING = ON (HISTORY_TABLE = dbo.File_History))
END
GO

-- Chat-Nachricht
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'CHAT_MESSAGE' AND type = 'U')
BEGIN
    CREATE TABLE [CHAT_MESSAGE](
        [ChatMessageId] int IDENTITY(1,1) primary key,
        [FromUserId] int FOREIGN KEY REFERENCES [USER](UserId) NOT NULL,
        [Message] varchar(MAX) NOT NULL,
        [ChatId] int FOREIGN KEY REFERENCES [CHAT](ChatId) NOT NULL,
        [TimeStamp] DATETIME NOT NULL,
    )
END GO

-- Benachrichtigung
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'NOTIFICATION' AND type = 'U')
BEGIN
    CREATE TABLE [NOTIFICATION](
        [NotificationId] int IDENTITY(1,1) primary key,
        [ToUserId] int FOREIGN KEY REFERENCES [USER](UserId) NOT NULL,
        [Message] varchar(MAX) NOT NULL,
        [IsRead] BIT NOT NULL,
        [RegardingId] int FOREIGN KEY REFERENCES [REFERENCE](ReferenceId) NOT NULL,
        [TimeStamp] DATETIME NOT NULL
    )
END

-- Tabelle: permissions
IF NOT EXISTS (SELECT * FROM sys.objects WHERE name = 'PERMISSIONS' AND type = 'U')
BEGIN
    CREATE TABLE PERMISSIONS (
        id INT IDENTITY(1,1) PRIMARY KEY NOT NULL,
        RoleId INT NOT NULL,
        PermissionName NVARCHAR(50) NOT NULL,
        FOREIGN KEY (RoleId) REFERENCES [ROLE](RoleId) ON DELETE CASCADE
    )
END
GO


-- Standard Datensätze erstellen
-- Rollen
INSERT INTO [dbo].ROLE (Name) VALUES 
    ('Admin'), -- 1
    ('Schüler'), -- 2
    ('Lehrer'); -- 3

GO

-- Rechte
INSERT INTO [dbo].[PERMISSIONS] (RoleId, PermissionName) VALUES
    (1,'admin'), -- Admin hat alle Rechte
    (2,'user'), -- Schüler hat nur normale Rechte
    (3,'teacher') -- Lehrer hat erweiterte Rechte, aber nicht alle wie Admin
GO

-- Benutzer
INSERT INTO [dbo].[User] (FirstName,LastName,RoleId) VALUES 
    ('Luca','Brüning',1),
    ('Simon','Krainert',1);
GO

-- Login
INSERT INTO [dbo].[LOGIN] (Username,UserPassword,UserId) VALUES 
    ('luca.bruening','$2y$12$p.JE.9o2a9Ea8HWQE7QUiOPDftMMEHo4eEVUvL5DlpUExtzCAOn/O',1),
    ('simon.krainert','$2y$12$gn6.Qu2VqsrNP5h.Nxe23OKWtP6zK4Z0C5cbSw3ft1TkmiremZ9LC',2);

-- Tabellen Nr.
INSERT INTO [dbo].[TABLENUMBER] (Name) VALUES 
    ('PROJECT'), -- 1
    ('TASK'), -- 2
    ('CHAT'), -- 3
    ('FOLDER'), -- 4
    ('FILE'), -- 5
    ('GROUP'), -- 6
    ('CALENDAR'), -- 7
    ('CALENDAR_ENTRY'); -- 8

-- Funktionen und Prozeduren erstellen
-- Bezug erstellen oder abrufen
CREATE PROCEDURE dbo.GetOrCreateReference
    @RegardingTableNumber INT,
    @RegardingId INT,
    @ReferenceId INT OUTPUT
AS
BEGIN
    select @ReferenceId = ReferenceId from [dbo].[REFERENCE] where RegardingTableNumber = @RegardingTableNumber and RegardingId = @RegardingId;

    if @ReferenceId is null
    BEGIN
        insert into [dbo].[REFERENCE] (RegardingTableNumber, RegardingId) values (@RegardingTableNumber, @RegardingId);
        select @ReferenceId = SCOPE_IDENTITY();
    END

    RETURN @ReferenceId;
END;

-- Erstelle Projekt Trigger
CREATE TRIGGER [dbo].[CreateProjectTrigger] ON [dbo].[PROJECT]
AFTER INSERT AS 
BEGIN
    SET NOCOUNT ON;
    -- Variablen deklarieren
    DECLARE @ProjectId int, @OwnerId int, @Title varchar(1000);
    DECLARE @DropOfFolderResultId int, @ProjectRererenceId int;

    SELECT @ProjectId = ProjectId, @OwnerId = OwnerId, @Title = Title FROM inserted;

    -- Projektabgabeordner erstellen
    INSERT INTO [dbo].[FOLDER] ([Name], [OwnerId], [IsRoot]) VALUES (
        'Projektabgabe - {' + CAST(@ProjectId AS varchar) + '} - ' + @Title,
        @OwnerId,
        1 -- Is Root
    );
    SELECT @DropOfFolderResultId = SCOPE_IDENTITY();

    -- Projekt Updaten
    UPDATE [dbo].[PROJECT] SET DropOfFolderId = @DropOfFolderResultId WHERE ProjectId = @ProjectId;

    -- Projekt-Referenz erstellen
    EXEC dbo.GetOrCreateReference 
        @RegardingTableNumber = 1, -- PROJECT
        @RegardingId = @ProjectId,
        @ReferenceId = @ProjectRererenceId OUTPUT

    -- Root-Ordner mit Projekt-Referenz erstellen
    INSERT INTO [dbo].[FOLDER] ([Name], [OwnerId], [IsRoot], [RegardingId]) VALUES (
        'Root',
        @OwnerId,
        1, -- Is Root
        @ProjectRererenceId
    );
END;