-- Datenbank erstellen
IF NOT EXISTS (SELECT * FROM sys.databases WHERE name = 'GoGroup')
BEGIN
    CREATE DATABASE [GoGroup]
END
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
        [RegardingId] int FOREIGN KEY REFERENCES [REFERENCE](ReferenceId) NOT NULL,
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
        [DropOfFolderId] int FOREIGN KEY REFERENCES [FOLDER](FolderId) NOT NULL
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
        [FileId] int IDENTITY(1,1) primary key,
        [Name] varchar(1000) NOT NULL,
        [FolderId] int FOREIGN KEY REFERENCES [FOLDER](FolderId) NOT NULL,
        [OwnerId] int FOREIGN KEY REFERENCES [USER](UserId) NOT NULL,
        [Content] VARBINARY(MAX) NOT NULL
    )
END GO

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